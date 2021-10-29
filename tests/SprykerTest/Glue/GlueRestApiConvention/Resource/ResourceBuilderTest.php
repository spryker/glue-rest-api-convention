<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Glue\GlueRestApiConvention\Resource;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionConfig;
use Spryker\Glue\GlueRestApiConvention\Resource\MissingResourceInterface;
use Spryker\Glue\GlueRestApiConvention\Resource\ResourceBuilder;
use Spryker\Glue\GlueRestApiConvention\Resource\ResourceInterface;
use Spryker\Glue\GlueRestApiConvention\Router\ResourceRouteCollection;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Glue\Kernel\BundleControllerAction;
use Spryker\Shared\Kernel\ClassResolver\Controller\AbstractControllerResolver;
use Spryker\Shared\Kernel\ClassResolver\Controller\ControllerNotFoundException;
use SprykerTest\Glue\GlueRestApiConvention\Resource\Stub\ControllerStub;

/**
 * Auto-generated group annotations
 *
 * @group Glue
 * @group GlueRestApiConvention
 * @group Resource
 * @group ResourceBuilderTest
 * Add your own group annotations below this line
 */
class ResourceBuilderTest extends Unit
{
    /**
     * @return void
     */
    public function testBuildPreFlightResource(): void
    {
        $expectedCorsAllowHeaders = ['foo' => 'bar'];
        $config = $this->createMock(GlueRestApiConventionConfig::class);
        $config->expects($this->once())
            ->method('getCorsAllowedHeaders')
            ->willReturn($expectedCorsAllowHeaders);
        $controllerResolverMock = $this->createNonCalledControllerResolverMock();
        $resourceRoutePluginMock = $this->createMock(ResourceRoutePluginInterface::class);

        $builder = new ResourceBuilder($controllerResolverMock, $config);
        $result = $builder->buildPreFlightResource(
            (new ResourceRouteCollection())->addGet('foo')->addDelete('bar'),
            $resourceRoutePluginMock,
        );

        $this->assertInstanceOf(ResourceInterface::class, $result);
        $response = call_user_func($result->getResource());
        $this->assertInstanceOf(GlueResponseTransfer::class, $response);
        $this->assertArrayHasKey('access-control-allow-methods', $response->getMeta());
        $this->assertSame('GET, DELETE', $response->getMeta()['access-control-allow-methods']);
        $this->assertArrayHasKey('access-control-allow-headers', $response->getMeta());
        $this->assertSame($expectedCorsAllowHeaders, $response->getMeta()['access-control-allow-headers']);
    }

    /**
     * @return void
     */
    public function testBuildMissingResource(): void
    {
        $config = $this->createMockConfig();
        $controllerResolverMock = $this->createNonCalledControllerResolverMock();

        $builder = new ResourceBuilder($controllerResolverMock, $config);
        $result = $builder->buildMissingResource();

        $this->assertInstanceOf(MissingResourceInterface::class, $result);
        $response = call_user_func($result->getResource());
        $this->assertInstanceOf(GlueResponseTransfer::class, $response);
        $this->assertSame('404', $response->getStatus());
        $this->assertSame('No route found', $response->getContent());
    }

    /**
     * @return void
     */
    public function testBuildResourceWithMissingController(): void
    {
        $expectController = 'Spryker\\SomeModule\\NonExistingController';
        $expectedAction = 'getAction';
        $result = $this->createResourceForControllerAction($expectController, $expectedAction);

        $this->assertInstanceOf(MissingResourceInterface::class, $result);
        $response = call_user_func($result->getResource());
        $this->assertInstanceOf(GlueResponseTransfer::class, $response);
        $this->assertSame('500', $response->getStatus());
        $this->assertStringContainsString('Can not resolve NonExistingController for your bundle', $response->getContent());
    }

    /**
     * @return void
     */
    public function testBuildResourceWithMissingControllerAction(): void
    {
        $result = $this->createResourceForControllerAction(ControllerStub::class, 'NonExisting');

        $this->assertInstanceOf(MissingResourceInterface::class, $result);
        $response = call_user_func($result->getResource());
        $this->assertInstanceOf(GlueResponseTransfer::class, $response);
        $this->assertSame('500', $response->getStatus());
        $this->assertSame('Neither NonExisting() nor NonExistingAction() found in ' . ControllerStub::class, $response->getContent());
    }

    /**
     * @return void
     */
    public function testBuildResourceWithControllerAndShortAction(): void
    {
        $controllerStub = new class {
            /**
             * @return int
             */
            public function get(): int
            {
                return 1;
            }
        };

        $result = $this->executeResourceBuilderWithMockedController($controllerStub);

        $this->assertInstanceOf(ResourceInterface::class, $result);
        $response = call_user_func($result->getResource());
        $this->assertSame(1, $response);
    }

    /**
     * @return void
     */
    public function testBuildResourceWithControllerAndLongAction(): void
    {
        $controllerStub = new class {
            /**
             * @return int
             */
            public function getAction(): int
            {
                return 1;
            }
        };
        $result = $this->executeResourceBuilderWithMockedController($controllerStub);

        $this->assertInstanceOf(ResourceInterface::class, $result);
        $response = call_user_func($result->getResource());
        $this->assertSame(1, $response);
    }

    /**
     * @param string $expectController
     * @param string $expectedAction
     *
     * @return \Spryker\Glue\GlueRestApiConvention\Resource\ResourceInterface
     */
    protected function createResourceForControllerAction(
        string $expectController,
        string $expectedAction
    ): ResourceInterface {
        $config = $this->createMockConfig();
        $resourceRoutePlugin = $this->createMock(ResourceRoutePluginInterface::class);
        $resourceRoutePlugin->expects($this->atLeast(1))
            ->method('getController')
            ->willReturn($expectController);

        $controllerResolverMock = $this->createMock(AbstractControllerResolver::class);

        if (class_exists($expectController)) {
            $controllerResolverMock->expects($this->once())
                ->method('resolve')
                ->willReturn(new $expectController());
        } else {
            $controllerResolverMock->expects($this->once())
                ->method('resolve')
                ->willReturnCallback(function (BundleControllerAction $bundleControllerAction): void {
                    throw new ControllerNotFoundException($bundleControllerAction);
                });
        }

        $builder = new ResourceBuilder($controllerResolverMock, $config);

        return $builder->buildResource(
            $resourceRoutePlugin,
            (new ResourceRouteCollection())->addGet($expectedAction),
            'GET',
        );
    }

    /**
     * @param object $controllerStub
     *
     * @return \Spryker\Glue\GlueRestApiConvention\Resource\ResourceInterface
     */
    protected function executeResourceBuilderWithMockedController($controllerStub): ResourceInterface
    {
        $config = $this->createMock(GlueRestApiConventionConfig::class);
        $config->expects($this->never())
            ->method('getCorsAllowedHeaders')
            ->willReturn([]);
        $expectedController = new $controllerStub();
        $resourceRoutePlugin = $this->createMock(ResourceRoutePluginInterface::class);
        $resourceRoutePlugin->expects($this->once())
            ->method('getController')
            ->willReturn(get_class($controllerStub));
        $mockControllerResolver = $this->createMock(AbstractControllerResolver::class);
        $mockControllerResolver->expects($this->once())
            ->method('resolve')
            ->willReturn($expectedController);

        $builder = new ResourceBuilder($mockControllerResolver, $config);

        return $builder->buildResource(
            $resourceRoutePlugin,
            (new ResourceRouteCollection())->addGet('get'),
            'GET',
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Kernel\ClassResolver\Controller\AbstractControllerResolver|mixed
     */
    protected function createNonCalledControllerResolverMock()
    {
        $controllerResolverMock = $this->createMock(AbstractControllerResolver::class);
        $controllerResolverMock->expects($this->never())
            ->method('resolve')
            ->willReturn(null);

        return $controllerResolverMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionConfig|mixed
     */
    protected function createMockConfig()
    {
        $config = $this->createMock(GlueRestApiConventionConfig::class);
        $config->expects($this->never())
            ->method('getCorsAllowedHeaders')
            ->willReturn([]);

        return $config;
    }
}
