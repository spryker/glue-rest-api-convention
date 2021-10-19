<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTests\Glue\GlueRestApiConvention\Router;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueVersionTransfer;
use Spryker\Glue\GlueRestApiConvention\Router\RequestResourcePluginFilter;
use Spryker\Glue\GlueRestApiConventionExtension\Plugin\ResourceRoutePluginInterface;
use Spryker\Glue\GlueRestApiConventionExtension\Plugin\ResourceRouteWithParentsPluginInterface;
use Spryker\Glue\GlueRestApiConventionExtension\Plugin\VersionedResourceRoutePluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueRestApiConvention
 * @group Router
 * @group ResourceRouterPluginFilterTest
 * Add your own group annotations below this line
 */
class ResourceRouterPluginFilterTest extends Unit
{
    /**
     * @var string
     */
    public const EXPECTED_RESOURCE = 'foo';

    /**
     * @return void
     */
    public function testNoRoutePlugins(): void
    {
        $routePlugins = $this->findPlugin([], null, null);
        $this->assertEmpty($routePlugins);
    }

    /**
     * @return void
     */
    public function testNoMatchingRoutePlugins(): void
    {
        $routePluginMock = $this->createSimpleRoutePluginMock(static::EXPECTED_RESOURCE);
        $resourceTransfer = new GlueResourceTransfer();
        $resourceTransfer->setType('bar')->setId(1);

        $routePlugins = $this->findPlugin([$routePluginMock], $resourceTransfer, null);
        $this->assertEmpty($routePlugins);
    }

    /**
     * @return void
     */
    public function testMatchingPlugin(): void
    {
        $routePluginMock = $this->createSimpleRoutePluginMock(static::EXPECTED_RESOURCE);
        $additionalPluginMock = $this->createSimpleRoutePluginMock('bar');
        $resourceTransfer = new GlueResourceTransfer();
        $resourceTransfer->setType(static::EXPECTED_RESOURCE)->setId(1);

        $routePlugins = $this->findPlugin([
            $additionalPluginMock,
            $routePluginMock,
        ], $resourceTransfer, null);
        $this->assertCount(1, $routePlugins);
        $this->assertInstanceOf(ResourceRoutePluginInterface::class, $routePlugins[0]);
        $this->assertSame($routePluginMock, $routePlugins[0]);
    }

    /**
     * @return void
     */
    public function testWithoutVersionProvidedForVersionedPlugins(): void
    {
        $expectedVersion = (new GlueVersionTransfer())->setMajor(1)->setMinor(0);
        $routePluginMock = $this->createMock(VersionedResourceRoutePluginInterface::class);
        $routePluginMock->expects($this->once())
            ->method('getResourceType')
            ->willReturn(static::EXPECTED_RESOURCE);
        $routePluginMock->expects($this->never())
            ->method('getMatchingVersion')
            ->willReturn($expectedVersion);
        $resourceTransfer = new GlueResourceTransfer();
        $resourceTransfer->setType(static::EXPECTED_RESOURCE)->setId(1);

        $routePlugins = $this->findPlugin([$routePluginMock], $resourceTransfer, null);
        $this->assertEmpty($routePlugins);
    }

    /**
     * @return void
     */
    public function testNoMatchVersionedPluginWithWrongVersion(): void
    {
        $expectedVersion = (new GlueVersionTransfer())->setMajor(1)->setMinor(0);
        $routePluginMock = $this->createVersionedRoutePluginMock(static::EXPECTED_RESOURCE, $expectedVersion);
        $resourceTransfer = new GlueResourceTransfer();
        $resourceTransfer->setType(static::EXPECTED_RESOURCE)->setId(1);

        $routePlugins = $this->findPlugin(
            [$routePluginMock],
            $resourceTransfer,
            (new GlueVersionTransfer())->setMajor(3)->setMinor(1)
        );
        $this->assertEmpty($routePlugins);
    }

    /**
     * @return void
     */
    public function testMatchVersionedPluginWithExactVersion(): void
    {
        $expectedVersion = (new GlueVersionTransfer())->setMajor(1)->setMinor(0);
        $routePluginMock = $this->createVersionedRoutePluginMock(static::EXPECTED_RESOURCE, $expectedVersion);
        $additionalRoutePluginMock = $this->createVersionedRoutePluginMock(static::EXPECTED_RESOURCE, (new GlueVersionTransfer())->setMajor(2)->setMinor(0));
        $resourceTransfer = new GlueResourceTransfer();
        $resourceTransfer->setType(static::EXPECTED_RESOURCE)->setId(1);

        $routePlugins = $this->findPlugin(
            [$additionalRoutePluginMock, $routePluginMock],
            $resourceTransfer,
            $expectedVersion
        );
        $this->assertCount(1, $routePlugins);
        $this->assertInstanceOf(VersionedResourceRoutePluginInterface::class, $routePlugins[0]);
        $this->assertSame($routePluginMock, $routePlugins[0]);
    }

    /**
     * @return void
     */
    public function testMatchVersionedPluginWithOnlyMajorVersion(): void
    {
        $expectedVersion = (new GlueVersionTransfer())->setMajor(1)->setMinor(null);
        $routePluginMock = $this->createMock(VersionedResourceRoutePluginInterface::class);
        $routePluginMock->expects($this->once())
            ->method('getResourceType')
            ->willReturn(static::EXPECTED_RESOURCE);
        $routePluginMock->expects($this->once())
            ->method('getMatchingVersion')
            ->willReturn($expectedVersion);
        $additionalRoutePluginMock = $this->createMock(VersionedResourceRoutePluginInterface::class);
        $additionalRoutePluginMock->expects($this->once())
            ->method('getResourceType')
            ->willReturn(static::EXPECTED_RESOURCE);
        $additionalRoutePluginMock->expects($this->once())
            ->method('getMatchingVersion')
            ->willReturn((new GlueVersionTransfer())->setMajor(2)->setMinor(null));
        $resourceTransfer = new GlueResourceTransfer();
        $resourceTransfer->setType(static::EXPECTED_RESOURCE)->setId(1);

        $routePlugins = $this->findPlugin(
            [$additionalRoutePluginMock, $routePluginMock],
            $resourceTransfer,
            (new GlueVersionTransfer())->setMajor(1)->setMinor(0)
        );
        $this->assertCount(1, $routePlugins);
        $this->assertInstanceOf(VersionedResourceRoutePluginInterface::class, $routePlugins[0]);
        $this->assertSame($routePluginMock, $routePlugins[0]);
    }

    /**
     * @return void
     */
    public function testMatchResourceWithParent(): void
    {
        $expectedParenResource = 'bar';
        $routePluginMock = $this->createRoutePluginWithParentsMock(static::EXPECTED_RESOURCE, [$expectedParenResource]);
        $additionalRoutePluginMock = $this->createRoutePluginWithParentsMock(static::EXPECTED_RESOURCE, ['baz']);
        $resourceTransfer = new GlueResourceTransfer();
        $resourceTransfer->setType(static::EXPECTED_RESOURCE)->setId(1);

        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setResource($resourceTransfer);
        $glueRequest->addParentResource($expectedParenResource, (new GlueResourceTransfer())->setType($expectedParenResource));
        $router = new RequestResourcePluginFilter();

        $routePlugins = $router->filterPlugins(
            $glueRequest,
            [$additionalRoutePluginMock, $routePluginMock]
        );
        $this->assertCount(1, $routePlugins);
        $this->assertInstanceOf(ResourceRouteWithParentsPluginInterface::class, $routePlugins[0]);
        $this->assertSame($routePluginMock, $routePlugins[0]);
    }

    /**
     * @return void
     */
    public function testMatchResourceWithMultipleParents(): void
    {
        $expectedParentResource = 'bar';
        $anotherExpectedParentResource = 'baz';
        $routePluginMock = $this->createRoutePluginWithParentsMock(static::EXPECTED_RESOURCE, [$expectedParentResource, $anotherExpectedParentResource]);
        $additionalRoutePluginMock = $this->createRoutePluginWithParentsMock(static::EXPECTED_RESOURCE, [$expectedParentResource, 'faz']);
        $resourceTransfer = new GlueResourceTransfer();
        $resourceTransfer->setType(static::EXPECTED_RESOURCE)->setId(1);

        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setResource($resourceTransfer);
        $glueRequest->addParentResource($expectedParentResource, (new GlueResourceTransfer())->setType($expectedParentResource));
        $glueRequest->addParentResource($anotherExpectedParentResource, (new GlueResourceTransfer())->setType($anotherExpectedParentResource));
        $router = new RequestResourcePluginFilter();

        $routePlugins = $router->filterPlugins(
            $glueRequest,
            [$additionalRoutePluginMock, $routePluginMock]
        );
        $this->assertCount(1, $routePlugins);
        $this->assertInstanceOf(ResourceRouteWithParentsPluginInterface::class, $routePlugins[0]);
        $this->assertSame($routePluginMock, $routePlugins[0]);
    }

    /**
     * @return void
     */
    public function testMatchResourceWithAdditionalParent(): void
    {
        $expectedParentResource = 'bar';
        $routePluginMock = $this->createRoutePluginWithParentsMock(static::EXPECTED_RESOURCE, [$expectedParentResource]);
        $additionalRoutePluginMock = $this->createRoutePluginWithParentsMock(static::EXPECTED_RESOURCE, ['faz']);
        $resourceTransfer = new GlueResourceTransfer();
        $resourceTransfer->setType(static::EXPECTED_RESOURCE)->setId(1);

        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setResource($resourceTransfer);
        $glueRequest->addParentResource($expectedParentResource, (new GlueResourceTransfer())->setType($expectedParentResource));
        $glueRequest->addParentResource('baz', (new GlueResourceTransfer())->setType('baz'));
        $router = new RequestResourcePluginFilter();

        $routePlugins = $router->filterPlugins(
            $glueRequest,
            [$additionalRoutePluginMock, $routePluginMock]
        );
        $this->assertCount(1, $routePlugins);
        $this->assertInstanceOf(ResourceRouteWithParentsPluginInterface::class, $routePlugins[0]);
        $this->assertSame($routePluginMock, $routePlugins[0]);
    }

    /**
     * @return void
     */
    public function testWithParentPluginsWithoutProvidedParents(): void
    {
        $routePluginMock = $this->createRoutePluginWithParentsMock(static::EXPECTED_RESOURCE);
        $routePluginMock->expects($this->never())
            ->method('getParentResourceTypes')
            ->willReturn(['bar']);
        $resourceTransfer = new GlueResourceTransfer();
        $resourceTransfer->setType(static::EXPECTED_RESOURCE)->setId(1);

        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setResource($resourceTransfer);
        $router = new RequestResourcePluginFilter();

        $routePlugins = $router->filterPlugins(
            $glueRequest,
            [$routePluginMock]
        );
        $this->assertEmpty($routePlugins);
    }

    /**
     * @param array<ResourceRoutePluginInterface> $routingPlugins
     * @param \Generated\Shared\Transfer\GlueResourceTransfer|null $resourceTransfer
     * @param \Generated\Shared\Transfer\GlueVersionTransfer|null $version
     *
     * @return array<\Spryker\Glue\GlueRestApiConventionExtension\Plugin\ResourceRoutePluginInterface>
     */
    protected function findPlugin(
        array $routingPlugins,
        ?GlueResourceTransfer $resourceTransfer = null,
        ?GlueVersionTransfer $version = null
    ): array {
        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setResource($resourceTransfer);
        $glueRequest->setVersion($version);
        $router = new RequestResourcePluginFilter();

        return $router->filterPlugins($glueRequest, $routingPlugins);
    }

    /**
     * @param string $expectedResource
     * @param array<string>|null $expectedParentResources
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueRestApiConventionExtension\Plugin\ResourceRouteWithParentsPluginInterface
     */
    protected function createRoutePluginWithParentsMock(
        string $expectedResource,
        ?array $expectedParentResources = null
    ): ResourceRouteWithParentsPluginInterface {
        $routePluginMock = $this->createMock(ResourceRouteWithParentsPluginInterface::class);
        $routePluginMock->expects($this->once())
            ->method('getResourceType')
            ->willReturn($expectedResource);
        $routePluginMock->expects($expectedParentResources === null ? $this->never() : $this->once())
            ->method('getParentResourceTypes')
            ->willReturn($expectedParentResources === null ? [] : $expectedParentResources);

        return $routePluginMock;
    }

    /**
     * @param string $expectedResource
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueRestApiConventionExtension\Plugin\ResourceRoutePluginInterface
     */
    protected function createSimpleRoutePluginMock(string $expectedResource): ResourceRoutePluginInterface
    {
        $routePluginMock = $this->createMock(ResourceRoutePluginInterface::class);
        $routePluginMock->expects($this->once())
            ->method('getResourceType')
            ->willReturn($expectedResource);

        return $routePluginMock;
    }

    /**
     * @param string $expectedResource
     * @param \Generated\Shared\Transfer\GlueVersionTransfer $expectedVersion
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueRestApiConventionExtension\Plugin\VersionedResourceRoutePluginInterface
     */
    protected function createVersionedRoutePluginMock(string $expectedResource, GlueVersionTransfer $expectedVersion): VersionedResourceRoutePluginInterface
    {
        $routePluginMock = $this->createMock(VersionedResourceRoutePluginInterface::class);
        $routePluginMock->expects($this->once())
            ->method('getResourceType')
            ->willReturn($expectedResource);
        $routePluginMock->expects($this->once())
            ->method('getMatchingVersion')
            ->willReturn($expectedVersion);

        return $routePluginMock;
    }
}
