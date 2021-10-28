<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueRestApiConvention\Resource;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueRestApiConvention\Resource\ResourceExecutor;
use Spryker\Glue\GlueRestApiConvention\Resource\ResourceInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueRestApiConvention
 * @group Resource
 * @group ResourceExecutorTest
 * Add your own group annotations below this line
 */
class ResourceExecutorTest extends Unit
{
    /**
     * @return void
     */
    public function testWithoutRequestedResource(): void
    {
        $resource = $this->createMock(ResourceInterface::class);
        $resource->expects($this->once())
            ->method('getResource')
            ->willReturn(function ($glueRequestTransfer): GlueResponseTransfer {
                $this->assertInstanceOf(GlueRequestTransfer::class, $glueRequestTransfer);
                $this->assertNull($glueRequestTransfer->getResource());

                return (new GlueResponseTransfer())->setStatus('200');
            });
        $glueRequestTransfer = new GlueRequestTransfer();
        $glueRequestTransfer->setResource(null);

        $resourceExecutor = new ResourceExecutor();
        $result = $resourceExecutor->executeResource($resource, $glueRequestTransfer);
        $this->assertInstanceOf(GlueResponseTransfer::class, $result);
        $this->assertSame('200', $result->getStatus());
    }

    /**
     * @return void
     */
    public function testWithoutRequestedId(): void
    {
        $resource = $this->createMock(ResourceInterface::class);
        $resource->expects($this->once())
            ->method('getResource')
            ->willReturn(function ($glueRequestTransfer): GlueResponseTransfer {
                $this->assertInstanceOf(GlueRequestTransfer::class, $glueRequestTransfer);
                $this->assertInstanceOf(GlueResourceTransfer::class, $glueRequestTransfer->getResource());
                $this->assertSame('test', $glueRequestTransfer->getResource()->getType());

                return (new GlueResponseTransfer())->setStatus('200');
            });
        $glueRequestTransfer = new GlueRequestTransfer();
        $glueRequestTransfer->setResource((new GlueResourceTransfer())->setType('test'));

        $resourceExecutor = new ResourceExecutor();
        $result = $resourceExecutor->executeResource($resource, $glueRequestTransfer);
        $this->assertInstanceOf(GlueResponseTransfer::class, $result);
        $this->assertSame('200', $result->getStatus());
    }

    /**
     * @return void
     */
    public function testControllerActionIsCalled(): void
    {
        $resource = $this->createMock(ResourceInterface::class);
        $resource->expects($this->once())
            ->method('getResource')
            ->willReturn(function ($resourceId, $glueRequestTransfer): GlueResponseTransfer {
                $this->assertSame(1, $resourceId);
                $this->assertInstanceOf(GlueRequestTransfer::class, $glueRequestTransfer);
                $this->assertInstanceOf(GlueResourceTransfer::class, $glueRequestTransfer->getResource());
                $this->assertSame('test', $glueRequestTransfer->getResource()->getType());

                return (new GlueResponseTransfer())->setStatus('200');
            });
        $glueRequestTransfer = new GlueRequestTransfer();
        $glueRequestTransfer->setResource((new GlueResourceTransfer())->setType('test')->setId(1));

        $resourceExecutor = new ResourceExecutor();
        $result = $resourceExecutor->executeResource($resource, $glueRequestTransfer);
        $this->assertInstanceOf(GlueResponseTransfer::class, $result);
        $this->assertSame('200', $result->getStatus());
    }

    /**
     * @return void
     */
    public function testControllerActionIsCalledWithParentResources(): void
    {
        $resource = $this->createMock(ResourceInterface::class);
        $resource->expects($this->once())
            ->method('getResource')
            ->willReturn(function ($resourceId, $glueRequestTransfer): GlueResponseTransfer {
                $this->assertSame(1, $resourceId);
                $this->assertInstanceOf(GlueRequestTransfer::class, $glueRequestTransfer);
                $this->assertInstanceOf(GlueResourceTransfer::class, $glueRequestTransfer->getResource());
                $this->assertSame('test', $glueRequestTransfer->getResource()->getType());
                $this->assertCount(1, $glueRequestTransfer->getParentResources());
                $this->assertArrayHasKey('parent_test', $glueRequestTransfer->getParentResources());
                $this->assertSame('parent_test', $glueRequestTransfer->getParentResources()['parent_test']->getType());
                $this->assertSame(2, $glueRequestTransfer->getParentResources()['parent_test']->getId());

                return (new GlueResponseTransfer())->setStatus('200');
            });
        $glueRequestTransfer = new GlueRequestTransfer();
        $glueRequestTransfer->setResource((new GlueResourceTransfer())->setType('test')->setId(1));
        $glueRequestTransfer->addParentResource(
            'parent_test',
            (new GlueResourceTransfer())
                ->setType('parent_test')
                ->setId(2),
        );

        $resourceExecutor = new ResourceExecutor();
        $result = $resourceExecutor->executeResource($resource, $glueRequestTransfer);
        $this->assertInstanceOf(GlueResponseTransfer::class, $result);
        $this->assertSame('200', $result->getStatus());
    }
}
