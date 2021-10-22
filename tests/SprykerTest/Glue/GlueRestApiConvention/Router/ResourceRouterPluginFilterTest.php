<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueRestApiConvention\Router;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueVersionTransfer;
use Spryker\Glue\GlueRestApiConvention\Exception\Router\AmbiguousRouteMatchingException;
use Spryker\Glue\GlueRestApiConvention\Router\RequestResourcePluginFilter;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResourceRouteWithParentsPluginInterface;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\VersionedResourceRoutePluginInterface;
use SprykerTest\Glue\GlueRestApiConvention\Router\Stub\RouterPluginStub;

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
     * @var string
     */
    public const EXPECTED_FIRST_PARENT_RESOURCE = 'bar';
    /**
     * @var string
     */
    public const EXPECTED_SECOND_PARENT_RESOURCE = 'baz';
    /**
     * @var array
     */
    public const EXPECTED_PARENT_RESOURCES = [self::EXPECTED_FIRST_PARENT_RESOURCE, self::EXPECTED_SECOND_PARENT_RESOURCE];
    /**
     * @var string
     */
    public const NON_MATCHING_RESOURCE = 'faz';

    /**
     * @return void
     */
    public function testNoRoutePlugins(): void
    {
        $routePlugin = $this->findPlugin([], null, null);
        $this->assertNull($routePlugin);
    }

    /**
     * @return void
     */
    public function testNoMatchingRoutePlugins(): void
    {
        $routePluginMock = $this->createSimpleRoutePluginMock(static::EXPECTED_RESOURCE);
        $resourceTransfer = new GlueResourceTransfer();
        $resourceTransfer->setType(static::EXPECTED_FIRST_PARENT_RESOURCE)->setId(1);

        $routePlugin = $this->findPlugin([$routePluginMock], $resourceTransfer, null);
        $this->assertNull($routePlugin);
    }

    /**
     * @return void
     */
    public function testMatchingPlugin(): void
    {
        $routePluginMock = $this->createSimpleRoutePluginMock(static::EXPECTED_RESOURCE);
        $additionalPluginMock = $this->createSimpleRoutePluginMock(static::EXPECTED_FIRST_PARENT_RESOURCE);
        $resourceTransfer = new GlueResourceTransfer();
        $resourceTransfer->setType(static::EXPECTED_RESOURCE)->setId(1);

        $routePlugin = $this->findPlugin([
            $additionalPluginMock,
            $routePluginMock,
        ], $resourceTransfer, null);
        $this->assertInstanceOf(ResourceRoutePluginInterface::class, $routePlugin);
        $this->assertSame($routePluginMock, $routePlugin);
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

        $routePlugin = $this->findPlugin(
            [$routePluginMock],
            $resourceTransfer,
            (new GlueVersionTransfer())->setMajor(3)->setMinor(1)
        );
        $this->assertNull($routePlugin);
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

        $routePlugin = $this->findPlugin(
            [$additionalRoutePluginMock, $routePluginMock],
            $resourceTransfer,
            $expectedVersion
        );
        $this->assertInstanceOf(VersionedResourceRoutePluginInterface::class, $routePlugin);
        $this->assertSame($routePluginMock, $routePlugin);
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

        $routePlugin = $this->findPlugin(
            [$additionalRoutePluginMock, $routePluginMock],
            $resourceTransfer,
            (new GlueVersionTransfer())->setMajor(1)->setMinor(0)
        );
        $this->assertInstanceOf(VersionedResourceRoutePluginInterface::class, $routePlugin);
        $this->assertSame($routePluginMock, $routePlugin);
    }

    /**
     * @return void
     */
    public function testMatchResourceWithParent(): void
    {
        $routePluginMock = $this->createRoutePluginWithParentsMock(static::EXPECTED_RESOURCE, [static::EXPECTED_FIRST_PARENT_RESOURCE]);
        $additionalRoutePluginMock = $this->createRoutePluginWithParentsMock(static::EXPECTED_RESOURCE, [static::EXPECTED_SECOND_PARENT_RESOURCE]);
        $resourceTransfer = new GlueResourceTransfer();
        $resourceTransfer->setType(static::EXPECTED_RESOURCE)->setId(1);

        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setResource($resourceTransfer);
        $glueRequest->addParentResource(static::EXPECTED_FIRST_PARENT_RESOURCE, (new GlueResourceTransfer())->setType(static::EXPECTED_FIRST_PARENT_RESOURCE));
        $router = new RequestResourcePluginFilter();

        $routePlugin = $router->filterPlugins(
            $glueRequest,
            [$additionalRoutePluginMock, $routePluginMock]
        );
        $this->assertInstanceOf(ResourceRouteWithParentsPluginInterface::class, $routePlugin);
        $this->assertSame($routePluginMock, $routePlugin);
    }

    /**
     * @return void
     */
    public function testMatchResourceWithMultipleParents(): void
    {
        $routePluginMock = $this->createRoutePluginWithParentsMock(static::EXPECTED_RESOURCE, [static::EXPECTED_FIRST_PARENT_RESOURCE, static::EXPECTED_SECOND_PARENT_RESOURCE]);
        $additionalRoutePluginMock = $this->createRoutePluginWithParentsMock(static::EXPECTED_RESOURCE, [static::EXPECTED_FIRST_PARENT_RESOURCE, self::NON_MATCHING_RESOURCE]);
        $resourceTransfer = new GlueResourceTransfer();
        $resourceTransfer->setType(static::EXPECTED_RESOURCE)->setId(1);

        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setResource($resourceTransfer);
        $glueRequest->addParentResource(static::EXPECTED_FIRST_PARENT_RESOURCE, (new GlueResourceTransfer())->setType(static::EXPECTED_FIRST_PARENT_RESOURCE));
        $glueRequest->addParentResource(static::EXPECTED_SECOND_PARENT_RESOURCE, (new GlueResourceTransfer())->setType(static::EXPECTED_SECOND_PARENT_RESOURCE));
        $router = new RequestResourcePluginFilter();

        $routePlugin = $router->filterPlugins(
            $glueRequest,
            [$additionalRoutePluginMock, $routePluginMock]
        );
        $this->assertInstanceOf(ResourceRouteWithParentsPluginInterface::class, $routePlugin);
        $this->assertSame($routePluginMock, $routePlugin);
    }

    /**
     * @return void
     */
    public function testMatchResourceWithAdditionalParent(): void
    {
        $routePluginMock = $this->createRoutePluginWithParentsMock(static::EXPECTED_RESOURCE, [static::EXPECTED_FIRST_PARENT_RESOURCE]);
        $additionalRoutePluginMock = $this->createRoutePluginWithParentsMock(static::EXPECTED_RESOURCE, [self::NON_MATCHING_RESOURCE]);
        $glueRequest = $this->createResourceRequestWithTwoParents();
        $router = new RequestResourcePluginFilter();

        $routePlugin = $router->filterPlugins(
            $glueRequest,
            [$additionalRoutePluginMock, $routePluginMock]
        );
        $this->assertInstanceOf(ResourceRouteWithParentsPluginInterface::class, $routePlugin);
        $this->assertSame($routePluginMock, $routePlugin);
    }

    /**
     * @return void
     */
    public function testWithParentPluginsWithoutProvidedParents(): void
    {
        $routePluginMock = $this->createRoutePluginWithParentsMock(static::EXPECTED_RESOURCE);
        $routePluginMock->expects($this->never())
            ->method('getParentResourceTypes')
            ->willReturn([static::EXPECTED_SECOND_PARENT_RESOURCE]);
        $resourceTransfer = new GlueResourceTransfer();
        $resourceTransfer->setType(static::EXPECTED_RESOURCE)->setId(1);

        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setResource($resourceTransfer);
        $router = new RequestResourcePluginFilter();

        $routePlugin = $router->filterPlugins(
            $glueRequest,
            [$routePluginMock]
        );
        $this->assertNull($routePlugin);
    }

    /**
     * @return void
     */
    public function testOnlyNewestMinorPluginIsMatchedWhenNoVersionIsGiven(): void
    {
        $oldestRoutingPlugin = new RouterPluginStub(
            static::EXPECTED_RESOURCE,
            static::EXPECTED_PARENT_RESOURCES,
            (new GlueVersionTransfer())->setMajor(1)->setMinor(0)
        );
        $olderRoutingPlugin = new RouterPluginStub(
            static::EXPECTED_RESOURCE,
            static::EXPECTED_PARENT_RESOURCES,
            (new GlueVersionTransfer())->setMajor(1)->setMinor(2)
        );
        $newestRoutingPlugin = new RouterPluginStub(
            static::EXPECTED_RESOURCE,
            static::EXPECTED_PARENT_RESOURCES,
            (new GlueVersionTransfer())->setMajor(1)->setMinor(3)
        );
        $nonVersionedPluginMock = $this->createRoutePluginWithParentsMock(static::EXPECTED_RESOURCE, static::EXPECTED_PARENT_RESOURCES);
        $nonMatchingPluginMock = new RouterPluginStub(
            static::NON_MATCHING_RESOURCE,
            static::EXPECTED_PARENT_RESOURCES,
            (new GlueVersionTransfer())->setMajor(1)->setMinor(3)
        );
        $nonMatchingParentsPluginMock = new RouterPluginStub(
            static::EXPECTED_RESOURCE,
            [static::NON_MATCHING_RESOURCE],
            (new GlueVersionTransfer())->setMajor(1)->setMinor(3)
        );

        $glueRequest = $this->createResourceRequestWithTwoParents();
        $router = new RequestResourcePluginFilter();

        $routePlugin = $router->filterPlugins(
            $glueRequest,
            [
                $nonMatchingParentsPluginMock,
                $olderRoutingPlugin,
                $oldestRoutingPlugin,
                $nonVersionedPluginMock,
                $newestRoutingPlugin,
                $nonMatchingPluginMock,
            ]
        );
        $this->assertInstanceOf(VersionedResourceRoutePluginInterface::class, $routePlugin);
        $this->assertSame($newestRoutingPlugin, $routePlugin);
    }

    /**
     * @return void
     */
    public function testAmbiguousMatchThrowsExceptionForNonVersionedRoutes(): void
    {
        $firstRoutePluginMock = $this->createRoutePluginWithParentsMock(static::EXPECTED_RESOURCE, static::EXPECTED_PARENT_RESOURCES);
        $secondRoutePluginMock = $this->createRoutePluginWithParentsMock(static::EXPECTED_RESOURCE, static::EXPECTED_PARENT_RESOURCES);
        $glueRequest = $this->createResourceRequestWithTwoParents();
        $router = new RequestResourcePluginFilter();

        $this->expectException(AmbiguousRouteMatchingException::class);
        $this->expectExceptionMessage(sprintf('More than one %s plugin was found to match', ResourceRoutePluginInterface::class));

        $router->filterPlugins(
            $glueRequest,
            [
                $firstRoutePluginMock,
                $secondRoutePluginMock,
            ]
        );
    }

    /**
     * @return void
     */
    public function testAmbiguousMatchThrowsExceptionForVersionedRoutes(): void
    {
        $expectedVersion = new GlueVersionTransfer();
        $expectedVersion->setMajor(1);
        $expectedVersion->setMinor(2);
        $firstRoutePluginMock = $this->createVersionedRoutePluginMock(static::EXPECTED_RESOURCE, $expectedVersion);
        $secondRoutePluginMock = $this->createVersionedRoutePluginMock(static::EXPECTED_RESOURCE, $expectedVersion);
        $glueRequest = $this->createResourceRequestWithTwoParents();
        $glueRequest->setVersion($expectedVersion);
        $router = new RequestResourcePluginFilter();

        $this->expectException(AmbiguousRouteMatchingException::class);
        $this->expectExceptionMessage(sprintf('More than one %s plugin was found to match', ResourceRoutePluginInterface::class));

        $router->filterPlugins(
            $glueRequest,
            [
                $firstRoutePluginMock,
                $secondRoutePluginMock,
            ]
        );
    }

    /**
     * @return void
     */
    public function testOnlyNonVersionedMatchesForVersionedRequest(): void
    {
        $firstRoutePluginMock = $this->createRoutePluginWithParentsMock(static::EXPECTED_RESOURCE, static::EXPECTED_PARENT_RESOURCES);
        $secondRoutePluginMock = $this->createRoutePluginWithParentsMock(static::EXPECTED_RESOURCE, [static::NON_MATCHING_RESOURCE]);
        $glueRequest = $this->createResourceRequestWithTwoParents();
        $expectedVersion = new GlueVersionTransfer();
        $expectedVersion->setMajor(1);
        $expectedVersion->setMinor(2);
        $glueRequest->setVersion($expectedVersion);
        $router = new RequestResourcePluginFilter();

        $routePlugin = $router->filterPlugins(
            $glueRequest,
            [
                $firstRoutePluginMock,
                $secondRoutePluginMock,
            ]
        );
        $this->assertInstanceOf(ResourceRouteWithParentsPluginInterface::class, $routePlugin);
        $this->assertSame($firstRoutePluginMock, $routePlugin);
    }

    /**
     * @param array<ResourceRoutePluginInterface> $routingPlugins
     * @param \Generated\Shared\Transfer\GlueResourceTransfer|null $resourceTransfer
     * @param \Generated\Shared\Transfer\GlueVersionTransfer|null $version
     *
     * @return \Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResourceRoutePluginInterface
     */
    protected function findPlugin(
        array $routingPlugins,
        ?GlueResourceTransfer $resourceTransfer = null,
        ?GlueVersionTransfer $version = null
    ): ?ResourceRoutePluginInterface {
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
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResourceRouteWithParentsPluginInterface
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
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResourceRoutePluginInterface
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
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\VersionedResourceRoutePluginInterface
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

    /**
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    protected function createResourceRequestWithTwoParents(): GlueRequestTransfer
    {
        $resourceTransfer = new GlueResourceTransfer();
        $resourceTransfer->setType(static::EXPECTED_RESOURCE)->setId(1);

        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setResource($resourceTransfer);
        $glueRequest->addParentResource(static::EXPECTED_FIRST_PARENT_RESOURCE, (new GlueResourceTransfer())->setType(static::EXPECTED_FIRST_PARENT_RESOURCE));
        $glueRequest->addParentResource(static::EXPECTED_SECOND_PARENT_RESOURCE, (new GlueResourceTransfer())->setType(static::EXPECTED_SECOND_PARENT_RESOURCE));

        return $glueRequest;
    }
}
