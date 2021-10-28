<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Bundles\GlueRestApiConvention\tests\SprykerTest\Glue\GlueRestApiConvention\Router;

use Codeception\Test\Unit;
use Spryker\Glue\GlueRestApiConvention\Router\ResourceRouteCollection;

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
 * @group ResourceRouteCollectionTest
 * Add your own group annotations below this line
 */
class ResourceRouteCollectionTest extends Unit
{
    /**
     * @dataProvider methodsProvider
     *
     * @param string $call
     * @param string $expectedActionName
     * @param string $expectedMethod
     * @param bool $expectedIsProtected
     * @param array $expectedContext
     *
     * @return void
     */
    public function testAddingMethods(
        string $call,
        string $expectedActionName,
        string $expectedMethod,
        bool $expectedIsProtected,
        array $expectedContext
    ): void {
        $resourceRouteCollection = new ResourceRouteCollection();
        $resourceRouteCollection->$call($expectedActionName, $expectedIsProtected, $expectedContext);

        $this->assertTrue($resourceRouteCollection->has($expectedMethod));
        $this->assertCount(1, $resourceRouteCollection->getAvailableMethods());
        $this->assertContains($expectedMethod, $resourceRouteCollection->getAvailableMethods());
        $result = $resourceRouteCollection->get($expectedMethod);
        $this->assertArrayHasKey(ResourceRouteCollection::IS_PROTECTED, $result);
        $this->assertSame($expectedIsProtected, $result[ResourceRouteCollection::IS_PROTECTED]);
        $this->assertArrayHasKey(ResourceRouteCollection::METHOD_CONTEXT, $result);
        $this->assertSame($expectedContext, $result[ResourceRouteCollection::METHOD_CONTEXT]);
        $this->assertArrayHasKey(ResourceRouteCollection::CONTROLLER_ACTION, $result);
        $this->assertSame($expectedActionName, $result[ResourceRouteCollection::CONTROLLER_ACTION]);
    }

    /**
     * @return void
     */
    public function testAddOptions(): void
    {
        $expectedActionName = 'optionsAction';
        $expectedContext = ['context_key' => 'context_value'];
        $expectedMethod = ResourceRouteCollection::METHOD_OPTIONS;
        $resourceRouteCollection = new ResourceRouteCollection();
        $resourceRouteCollection->addOptions($expectedActionName, $expectedContext);

        $this->assertTrue($resourceRouteCollection->has($expectedMethod));
        $this->assertCount(1, $resourceRouteCollection->getAvailableMethods());
        $this->assertContains($expectedMethod, $resourceRouteCollection->getAvailableMethods());
        $result = $resourceRouteCollection->get($expectedMethod);
        $this->assertArrayHasKey(ResourceRouteCollection::IS_PROTECTED, $result);
        $this->assertFalse($result[ResourceRouteCollection::IS_PROTECTED]);
        $this->assertArrayHasKey(ResourceRouteCollection::METHOD_CONTEXT, $result);
        $this->assertSame($expectedContext, $result[ResourceRouteCollection::METHOD_CONTEXT]);
        $this->assertArrayHasKey(ResourceRouteCollection::CONTROLLER_ACTION, $result);
        $this->assertSame($expectedActionName, $result[ResourceRouteCollection::CONTROLLER_ACTION]);
    }

    /**
     * @return array<array>
     */
    public function methodsProvider(): array
    {
        return [
            [
                'addGet',
                'getAction',
                ResourceRouteCollection::METHOD_GET,
                false,
                ['context_key' => 'context_value'],
            ], [
                'addGetCollection',
                'getCollectionAction',
                ResourceRouteCollection::METHOD_GET_COLLECTION,
                false,
                ['context_key' => 'context_value'],
            ], [
                'addDelete',
                'deleteAction',
                ResourceRouteCollection::METHOD_DELETE,
                false,
                ['context_key' => 'context_value'],
            ], [
                'addPost',
                'postAction',
                ResourceRouteCollection::METHOD_POST,
                false,
                ['context_key' => 'context_value'],
            ], [
                'addPatch',
                'patchAction',
                ResourceRouteCollection::METHOD_PATCH,
                false,
                ['context_key' => 'context_value'],
            ],
        ];
    }
}
