<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Bundles\GlueRestApiConvention\tests\SprykerTest\Glue\GlueRestApiConvention\Router;

use Closure;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Spryker\Glue\GlueRestApiConvention\Exception\Router\MissingRequestMethodException;
use Spryker\Glue\GlueRestApiConvention\Resource\MissingResource;
use Spryker\Glue\GlueRestApiConvention\Resource\Resource;
use Spryker\Glue\GlueRestApiConvention\Resource\ResourceBuilder;
use Spryker\Glue\GlueRestApiConvention\Resource\ResourceBuilderInterface;
use Spryker\Glue\GlueRestApiConvention\Resource\ResourceInterface;
use Spryker\Glue\GlueRestApiConvention\Resource\ResourceRouteCollectionInterface;
use Spryker\Glue\GlueRestApiConvention\Router\RequestResourcePluginFilterInterface;
use Spryker\Glue\GlueRestApiConvention\Router\ResourceRouteCollection;
use Spryker\Glue\GlueRestApiConvention\Router\RestRequestRoutingMatcher;
use Spryker\Glue\GlueRestApiConvention\Router\RestRequestRoutingMatcherInterface;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResourceRoutePluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group Bundles
 * @group GlueRestApiConvention
 * @group tests
 * @group SprykerTest
 * @group Glue
 * @group GlueRestApiConvention
 * @group Router
 * @group RestRequestRoutingMatcherTest
 * Add your own group annotations below this line
 */
class RestRequestRoutingMatcherTest extends Unit
{
    /**
     * @return void
     */
    public function testMatchNoRoutePluginWithMissingResource(): void
    {
        $expectedResource = new MissingResource('404', 'No route matching');
        $resourceBuilderMock = $this->createMock(ResourceBuilder::class);
        $resourceBuilderMock->expects($this->once())
            ->method('buildMissingResource')
            ->willReturn($expectedResource);
        $requestMatcher = $this->createRequestMatcher($resourceBuilderMock);

        $result = $requestMatcher->matchRequest($this->createGlueRequest(), [null]);

        $this->assertSame($expectedResource, $result);
    }

    /**
     * @return void
     */
    public function testMatchingRoutePluginWithNonMatchingMethods(): void
    {
        $expectedResource = new MissingResource('404', 'No method matching');
        $resourceBuilderMock = $this->createMock(ResourceBuilder::class);
        $resourceBuilderMock->expects($this->once())
            ->method('buildMissingResource')
            ->willReturn($expectedResource);
        $resourceRoutePluginMock = $this->createResourceRoutePluginMock();

        $requestMatcher = $this->createRequestMatcher($resourceBuilderMock);
        $result = $requestMatcher->matchRequest($this->createGlueRequest(), [$resourceRoutePluginMock]);

        $this->assertSame($expectedResource, $result);
    }

    /**
     * @return void
     */
    public function testEmptyRequestMethodWillThrowException(): void
    {
        $expectedResourceCollection = function (ResourceRouteCollectionInterface $routeCollection) {
            $routeCollection->addGet('get');

            return $routeCollection;
        };
        $resourceRoutePluginMock = $this->createResourceRoutePluginMock($expectedResourceCollection);
        $resourceBuilderMock = $this->createMock(ResourceBuilder::class);
        $resourceBuilderMock->expects($this->never())
            ->method('buildMissingResource')
            ->willReturn(new MissingResource('foo', 'bar'));

        $requestMatcher = $this->createRequestMatcher($resourceBuilderMock);
        $glueRequest = $this->createGlueRequest();
        $glueRequest->setMethod('');

        $this->expectException(MissingRequestMethodException::class);
        $this->expectExceptionMessage('Empty request method can not be mapped to a controller action');

        $requestMatcher->matchRequest($glueRequest, [$resourceRoutePluginMock]);
    }

    /**
     * @return void
     */
    public function testMatchingRoutePluginWithDefaultOptionsMethod(): void
    {
        $expectedResourceCollection = function (ResourceRouteCollectionInterface $routeCollection) {
            $routeCollection->addGet('get');

            return $routeCollection;
        };
        $resourceRoutePluginMock = $this->createResourceRoutePluginMock($expectedResourceCollection);
        $resourceBuilderMock = $this->createMock(ResourceBuilder::class);
        $resourceBuilderMock->expects($this->never())
            ->method('buildMissingResource')
            ->willReturn(new MissingResource('foo', 'bar'));
        $resourceBuilderMock->expects($this->once())
            ->method('buildPreFlightResource')
            ->willReturnCallback(function (ResourceRouteCollectionInterface $resourceRouteCollection, ResourceRoutePluginInterface $resourceRoutePlugin): ResourceInterface {
                return new Resource(function (): void {
                    //do nothing
                }, $resourceRoutePlugin, $resourceRouteCollection);
            });

        $requestMatcher = $this->createRequestMatcher($resourceBuilderMock);
        $glueRequest = $this->createGlueRequest();
        $glueRequest->setMethod('OPTIONS');
        $result = $requestMatcher->matchRequest($glueRequest, [$resourceRoutePluginMock]);

        $this->assertInstanceOf(ResourceInterface::class, $result);
        $this->assertTrue($result->getMatchingResourceCollection()->has(ResourceRouteCollection::METHOD_GET));
        $this->assertIsCallable($result->getResource());
    }

    /**
     * @return void
     */
    public function testMatchingRoutePluginWithGetMethod(): void
    {
        $expectedResourceCollection = function (ResourceRouteCollectionInterface $routeCollection) {
            $routeCollection->addGet('get');

            return $routeCollection;
        };
        $expectedRequestMethod = ResourceRouteCollection::METHOD_GET;

        $result = $this->executeSuccessFullTest($expectedResourceCollection, $expectedRequestMethod);

        $this->assertInstanceOf(ResourceInterface::class, $result);
        $this->assertTrue($result->getMatchingResourceCollection()->has($expectedRequestMethod));
        $this->assertIsCallable($result->getResource());
    }

    /**
     * @return void
     */
    public function testMatchingRoutePluginWithPostMethod(): void
    {
        $expectedResourceCollection = function (ResourceRouteCollectionInterface $routeCollection) {
            $routeCollection->addGetCollection('collection');

            return $routeCollection;
        };
        $result = $this->executeSuccessFullTest($expectedResourceCollection, 'GET', null);

        $this->assertInstanceOf(ResourceInterface::class, $result);
        $this->assertTrue($result->getMatchingResourceCollection()->has(ResourceRouteCollection::METHOD_GET_COLLECTION));
        $this->assertIsCallable($result->getResource());
    }

    /**
     * @return void
     */
    public function testMatchingRoutePluginWithPatchMethod(): void
    {
        $expectedResourceCollection = function (ResourceRouteCollectionInterface $routeCollection) {
            $routeCollection->addPatch('patch');

            return $routeCollection;
        };
        $expectedRequestMethod = ResourceRouteCollection::METHOD_PATCH;

        $result = $this->executeSuccessFullTest($expectedResourceCollection, $expectedRequestMethod);

        $this->assertInstanceOf(ResourceInterface::class, $result);
        $this->assertTrue($result->getMatchingResourceCollection()->has($expectedRequestMethod));
        $this->assertIsCallable($result->getResource());
    }

    /**
     * @return void
     */
    public function testMatchingRoutePluginWithDeleteMethod(): void
    {
        $expectedResourceCollection = function (ResourceRouteCollectionInterface $routeCollection) {
            $routeCollection->addDelete('delete');

            return $routeCollection;
        };
        $expectedRequestMethod = ResourceRouteCollection::METHOD_DELETE;

        $result = $this->executeSuccessFullTest($expectedResourceCollection, $expectedRequestMethod);

        $this->assertInstanceOf(ResourceInterface::class, $result);
        $this->assertTrue($result->getMatchingResourceCollection()->has($expectedRequestMethod));
        $this->assertIsCallable($result->getResource());
    }

    /**
     * @return void
     */
    public function testMatchingRoutePluginWithConfiguredOptionsMethod(): void
    {
        $expectedResourceCollection = function (ResourceRouteCollectionInterface $routeCollection) {
            $routeCollection->addOptions('options');

            return $routeCollection;
        };
        $expectedRequestMethod = ResourceRouteCollection::METHOD_OPTIONS;

        $result = $this->executeSuccessFullTest($expectedResourceCollection, $expectedRequestMethod);

        $this->assertInstanceOf(ResourceInterface::class, $result);
        $this->assertTrue($result->getMatchingResourceCollection()->has($expectedRequestMethod));
        $this->assertIsCallable($result->getResource());
    }

    /**
     * @param \Spryker\Glue\GlueRestApiConvention\Resource\ResourceBuilderInterface $resourceBuilder
     *
     * @return \Spryker\Glue\GlueRestApiConvention\Router\RestRequestRoutingMatcherInterface
     */
    protected function createRequestMatcher(ResourceBuilderInterface $resourceBuilder): RestRequestRoutingMatcherInterface
    {
        $filterMock = $this->createMock(RequestResourcePluginFilterInterface::class);
        $filterMock
            ->expects($this->once())
            ->method('filterPlugins')
            ->willReturnCallback(function (GlueRequestTransfer $requestTransfer, array $plugins) {
                return $plugins[0];
            });

        return new RestRequestRoutingMatcher($filterMock, $resourceBuilder);
    }

    /**
     * @param string $method
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    protected function createGlueRequest(string $method = 'GET'): GlueRequestTransfer
    {
        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setResource((new GlueResourceTransfer())->setType('foo')->setId(1));
        $glueRequest->setMethod($method);

        return $glueRequest;
    }

    /**
     * @param \Closure|null $expectedResourceCollection
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResourceRoutePluginInterface
     */
    protected function createResourceRoutePluginMock(?Closure $expectedResourceCollection = null): ResourceRoutePluginInterface
    {
        $resourceRoutePluginMock = $this->createMock(ResourceRoutePluginInterface::class);
        if ($expectedResourceCollection) {
            $resourceRoutePluginMock
                ->expects($this->once())
                ->method('configure')
                ->willReturnCallback($expectedResourceCollection);
        } else {
            $resourceRoutePluginMock
                ->expects($this->once())
                ->method('configure')
                ->willReturnArgument(0);
        }

        return $resourceRoutePluginMock;
    }

    /**
     * @param \Closure $expectedResourceCollection
     * @param string $expectedRequestMethod
     * @param int|null $resourceId
     *
     * @return \Spryker\Glue\GlueRestApiConvention\Resource\ResourceInterface
     */
    protected function executeSuccessFullTest(
        Closure $expectedResourceCollection,
        string $expectedRequestMethod,
        ?int $resourceId = 1
    ): ResourceInterface {
        $resourceRoutePluginMock = $this->createResourceRoutePluginMock($expectedResourceCollection);
        $resourceBuilderMock = $this->createMock(ResourceBuilder::class);
        $resourceBuilderMock->expects($this->never())
            ->method('buildMissingResource')
            ->willReturn(new MissingResource('foo', 'bar'));
        $resourceBuilderMock->expects($this->never())
            ->method('buildPreFlightResource')
            ->willReturnCallback(function (ResourceRouteCollectionInterface $resourceRouteCollection, ResourceRoutePluginInterface $resourceRoutePlugin): ResourceInterface {
                return new Resource(function (): void {
                    //do nothing
                }, $resourceRoutePlugin, $resourceRouteCollection);
            });
        $resourceBuilderMock->expects($this->once())
            ->method('buildResource')
            ->willReturnCallback(function (
                ResourceRoutePluginInterface $routePlugin,
                ResourceRouteCollectionInterface $resourceRouteCollection
            ): ResourceInterface {
                return new Resource(function (): void {
                    //do nothing
                }, $routePlugin, $resourceRouteCollection);
            });

        $requestMatcher = $this->createRequestMatcher($resourceBuilderMock);
        $glueRequest = $this->createGlueRequest();
        $glueRequest->setMethod($expectedRequestMethod);
        $glueRequest->getResource()->setId($resourceId);

        return $requestMatcher->matchRequest($glueRequest, [$resourceRoutePluginMock]);
    }
}
