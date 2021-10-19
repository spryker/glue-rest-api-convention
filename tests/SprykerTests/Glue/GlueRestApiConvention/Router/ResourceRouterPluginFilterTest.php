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
use Spryker\Glue\GlueRestApiConvention\Exception\Router\AmbiguousRouteMatchingException;
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
     * @return void
     */
    public function testNoRoutePlugins(): void
    {
        $route = $this->findPlugin([], null, null);
        $this->assertNull($route);
    }

    /**
     * @return void
     */
    public function testNoMatchingRoutePlugins(): void
    {
        $routePluginMock = $this->createMock(ResourceRoutePluginInterface::class);
        $routePluginMock->expects($this->once())
            ->method('getResourceType')
            ->willReturn('bar');
        $resourceTransfer = new GlueResourceTransfer();
        $resourceTransfer->setType('foo')->setId(1);

        $route = $this->findPlugin([$routePluginMock], $resourceTransfer, null);
        $this->assertNull($route);
    }

    /**
     * @return void
     */
    public function testAmbiguousMatchingRoutesWillThrowException(): void
    {
        $firstRoutePluginMock = $this->createMock(ResourceRoutePluginInterface::class);
        $firstRoutePluginMock->expects($this->once())
            ->method('getResourceType')
            ->willReturn('foo');
        $secondRoutePluginMock = $this->createMock(ResourceRoutePluginInterface::class);
        $secondRoutePluginMock->expects($this->once())
            ->method('getResourceType')
            ->willReturn('foo');
        $resourceTransfer = new GlueResourceTransfer();
        $resourceTransfer->setType('foo')->setId(1);

        $this->expectException(AmbiguousRouteMatchingException::class);
        $this->expectExceptionMessage(sprintf(
            'More than one %s matched, did you missed to add %s or %s to one of the plugins?',
            ResourceRoutePluginInterface::class,
            VersionedResourceRoutePluginInterface::class,
            ResourceRouteWithParentsPluginInterface::class
        ));
        $this->findPlugin([$firstRoutePluginMock, $secondRoutePluginMock], $resourceTransfer, null);
    }

    /**
     * @return void
     */
    public function testMatchingPlugin(): void
    {
        $routePluginMock = $this->createMock(ResourceRoutePluginInterface::class);
        $routePluginMock->expects($this->once())
            ->method('getResourceType')
            ->willReturn('foo');
        $resourceTransfer = new GlueResourceTransfer();
        $resourceTransfer->setType('foo')->setId(1);

        $route = $this->findPlugin([$routePluginMock], $resourceTransfer, null);
        $this->assertInstanceOf(ResourceRoutePluginInterface::class, $route);
    }

    /**
     * @return void
     */
    public function testWithoutVersionProvidedForVersionedPlugins(): void
    {
        $routePluginMock = $this->createMock(VersionedResourceRoutePluginInterface::class);
        $routePluginMock->expects($this->once())
            ->method('getResourceType')
            ->willReturn('foo');
        $routePluginMock->expects($this->never())
            ->method('getMatchingVersion')
            ->willReturn((new GlueVersionTransfer())->setMajor(1)->setMinor(0));
        $resourceTransfer = new GlueResourceTransfer();
        $resourceTransfer->setType('foo')->setId(1);

        $route = $this->findPlugin([$routePluginMock], $resourceTransfer, null);
        $this->assertNull($route);
    }

    /**
     * @return void
     */
    public function testNoMatchVersionedPluginWithWrongVersion(): void
    {
        $routePluginMock = $this->createMock(VersionedResourceRoutePluginInterface::class);
        $routePluginMock->expects($this->once())
            ->method('getResourceType')
            ->willReturn('foo');
        $routePluginMock->expects($this->once())
            ->method('getMatchingVersion')
            ->willReturn((new GlueVersionTransfer())->setMajor(1)->setMinor(0));
        $resourceTransfer = new GlueResourceTransfer();
        $resourceTransfer->setType('foo')->setId(1);

        $route = $this->findPlugin(
            [$routePluginMock],
            $resourceTransfer,
            (new GlueVersionTransfer())->setMajor(3)->setMinor(1)
        );
        $this->assertNull($route);
    }

    /**
     * @return void
     */
    public function testMatchVersionedPluginWithExactVersion(): void
    {
        $routePluginMock = $this->createMock(VersionedResourceRoutePluginInterface::class);
        $routePluginMock->expects($this->once())
            ->method('getResourceType')
            ->willReturn('foo');
        $routePluginMock->expects($this->once())
            ->method('getMatchingVersion')
            ->willReturn((new GlueVersionTransfer())->setMajor(1)->setMinor(0));
        $resourceTransfer = new GlueResourceTransfer();
        $resourceTransfer->setType('foo')->setId(1);

        $route = $this->findPlugin(
            [$routePluginMock],
            $resourceTransfer,
            (new GlueVersionTransfer())->setMajor(1)->setMinor(0)
        );
        $this->assertInstanceOf(VersionedResourceRoutePluginInterface::class, $route);
    }

    /**
     * @return void
     */
    public function testMatchVersionedPluginWithOnlyMajorVersion(): void
    {
        $routePluginMock = $this->createMock(VersionedResourceRoutePluginInterface::class);
        $routePluginMock->expects($this->once())
            ->method('getResourceType')
            ->willReturn('foo');
        $routePluginMock->expects($this->once())
            ->method('getMatchingVersion')
            ->willReturn((new GlueVersionTransfer())->setMajor(1)->setMinor(null));
        $resourceTransfer = new GlueResourceTransfer();
        $resourceTransfer->setType('foo')->setId(1);

        $route = $this->findPlugin(
            [$routePluginMock],
            $resourceTransfer,
            (new GlueVersionTransfer())->setMajor(1)->setMinor(0)
        );
        $this->assertInstanceOf(VersionedResourceRoutePluginInterface::class, $route);
    }

    /**
     * @return void
     */
    public function testMatchResourceWithParent(): void
    {
        $routePluginMock = $this->createMock(ResourceRouteWithParentsPluginInterface::class);
        $routePluginMock->expects($this->once())
            ->method('getResourceType')
            ->willReturn('foo');
        $routePluginMock->expects($this->once())
            ->method('getParentResourceTypes')
            ->willReturn(['bar']);
        $resourceTransfer = new GlueResourceTransfer();
        $resourceTransfer->setType('foo')->setId(1);

        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setResource($resourceTransfer);
        $glueRequest->addParentResource('bar', (new GlueResourceTransfer())->setType('bar'));
        $router = new RequestResourcePluginFilter();

        $route = $router->filterPlugins(
            $glueRequest,
            [$routePluginMock]
        );
        $this->assertInstanceOf(ResourceRouteWithParentsPluginInterface::class, $route);
    }

    /**
     * @return void
     */
    public function testMatchResourceWithMultipleParents(): void
    {
        $routePluginMock = $this->createMock(ResourceRouteWithParentsPluginInterface::class);
        $routePluginMock->expects($this->once())
            ->method('getResourceType')
            ->willReturn('foo');
        $routePluginMock->expects($this->once())
            ->method('getParentResourceTypes')
            ->willReturn(['bar', 'baz']);
        $resourceTransfer = new GlueResourceTransfer();
        $resourceTransfer->setType('foo')->setId(1);

        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setResource($resourceTransfer);
        $glueRequest->addParentResource('bar', (new GlueResourceTransfer())->setType('bar'));
        $glueRequest->addParentResource('baz', (new GlueResourceTransfer())->setType('baz'));
        $router = new RequestResourcePluginFilter();

        $route = $router->filterPlugins(
            $glueRequest,
            [$routePluginMock]
        );
        $this->assertInstanceOf(ResourceRouteWithParentsPluginInterface::class, $route);
    }

    /**
     * @return void
     */
    public function testMatchResourceWithAdditionalParent(): void
    {
        $routePluginMock = $this->createMock(ResourceRouteWithParentsPluginInterface::class);
        $routePluginMock->expects($this->once())
            ->method('getResourceType')
            ->willReturn('foo');
        $routePluginMock->expects($this->once())
            ->method('getParentResourceTypes')
            ->willReturn(['bar']);
        $resourceTransfer = new GlueResourceTransfer();
        $resourceTransfer->setType('foo')->setId(1);

        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setResource($resourceTransfer);
        $glueRequest->addParentResource('bar', (new GlueResourceTransfer())->setType('bar'));
        $glueRequest->addParentResource('baz', (new GlueResourceTransfer())->setType('baz'));
        $router = new RequestResourcePluginFilter();

        $route = $router->filterPlugins(
            $glueRequest,
            [$routePluginMock]
        );
        $this->assertInstanceOf(ResourceRouteWithParentsPluginInterface::class, $route);
    }

    /**
     * @return void
     */
    public function testWithParentPluginsWithoutProvidedParents(): void
    {
        $routePluginMock = $this->createMock(ResourceRouteWithParentsPluginInterface::class);
        $routePluginMock->expects($this->once())
            ->method('getResourceType')
            ->willReturn('foo');
        $routePluginMock->expects($this->once())
            ->method('getParentResourceTypes')
            ->willReturn(['bar']);
        $resourceTransfer = new GlueResourceTransfer();
        $resourceTransfer->setType('foo')->setId(1);

        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setResource($resourceTransfer);
        $router = new RequestResourcePluginFilter();

        $route = $router->filterPlugins(
            $glueRequest,
            [$routePluginMock]
        );
        $this->assertNull($route);
    }

    /**
     * @return void
     */
    public function testMatchAlternativeResource(): void
    {
    }

    /**
     * @param array<ResourceRoutePluginInterface> $routingPlugins
     * @param \Generated\Shared\Transfer\GlueResourceTransfer|null $resourceTransfer
     * @param \Generated\Shared\Transfer\GlueVersionTransfer|null $version
     *
     * @return \Spryker\Glue\GlueRestApiConventionExtension\Plugin\ResourceRoutePluginInterface|null
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
}
