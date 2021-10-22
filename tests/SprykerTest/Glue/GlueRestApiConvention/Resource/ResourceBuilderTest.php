<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Glue\GlueRestApiConvention\Resource;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionConfig;
use Spryker\Glue\GlueRestApiConvention\Resource\ResourceBuilder;
use Spryker\Glue\GlueRestApiConvention\Router\ResourceRouteCollection;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Resource\MissingResourceInterface;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Resource\ResourceInterface;

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

        $builder = new ResourceBuilder($config);
        $result = $builder->buildPreFlightResource((new ResourceRouteCollection())->addGet('foo')->addDelete('bar'));

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
        $config = $this->createMock(GlueRestApiConventionConfig::class);
        $config->expects($this->never())
            ->method('getCorsAllowedHeaders')
            ->willReturn([]);

        $builder = new ResourceBuilder($config);
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
        $expectController = 'NonExistingController';
        $expectedAction = 'getAction';
        $result = $this->createResourceForControllerAction($expectController, $expectedAction);

        $this->assertInstanceOf(MissingResourceInterface::class, $result);
        $response = call_user_func($result->getResource());
        $this->assertInstanceOf(GlueResponseTransfer::class, $response);
        $this->assertSame('500', $response->getStatus());
        $this->assertSame('Controller NonExistingController not found', $response->getContent());
    }

    /**
     * @return void
     */
    public function testBuildResourceWithMissingControllerAction(): void
    {
        $result = $this->createResourceForControllerAction(ResourceBuilderTest::class, 'get');

        $this->assertInstanceOf(MissingResourceInterface::class, $result);
        $response = call_user_func($result->getResource());
        $this->assertInstanceOf(GlueResponseTransfer::class, $response);
        $this->assertSame('500', $response->getStatus());
        $this->assertSame('Neither get() nor getAction() found in ' . ResourceBuilderTest::class, $response->getContent());
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
        $config = $this->createMock(GlueRestApiConventionConfig::class);
        $config->expects($this->never())
            ->method('getCorsAllowedHeaders')
            ->willReturn([]);
        $resourceRoutePlugin = $this->createMock(ResourceRoutePluginInterface::class);
        $resourceRoutePlugin->expects($this->once())
            ->method('getController')
            ->willReturn(get_class($controllerStub));

        $builder = new ResourceBuilder($config);

        $result = $builder->buildResource(
            $resourceRoutePlugin,
            (new ResourceRouteCollection())->addGet('get'),
            'GET'
        );

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
        $config = $this->createMock(GlueRestApiConventionConfig::class);
        $config->expects($this->never())
            ->method('getCorsAllowedHeaders')
            ->willReturn([]);
        $resourceRoutePlugin = $this->createMock(ResourceRoutePluginInterface::class);
        $resourceRoutePlugin->expects($this->once())
            ->method('getController')
            ->willReturn(get_class($controllerStub));

        $builder = new ResourceBuilder($config);

        $result = $builder->buildResource(
            $resourceRoutePlugin,
            (new ResourceRouteCollection())->addGet('get'),
            'GET'
        );

        $this->assertInstanceOf(ResourceInterface::class, $result);
        $response = call_user_func($result->getResource());
        $this->assertSame(1, $response);
    }

    /**
     * @param string $expectController
     * @param string $expectedAction
     *
     * @return \Spryker\Glue\GlueRestApiConventionExtension\Dependency\Resource\ResourceInterface
     */
    protected function createResourceForControllerAction(
        string $expectController,
        string $expectedAction
    ): ResourceInterface {
        $config = $this->createMock(GlueRestApiConventionConfig::class);
        $config->expects($this->never())
            ->method('getCorsAllowedHeaders')
            ->willReturn([]);
        $resourceRoutePlugin = $this->createMock(ResourceRoutePluginInterface::class);
        $resourceRoutePlugin->expects($this->once())
            ->method('getController')
            ->willReturn($expectController);

        $builder = new ResourceBuilder($config);

        return $builder->buildResource(
            $resourceRoutePlugin,
            (new ResourceRouteCollection())->addGet($expectedAction),
            'GET'
        );
    }
}
