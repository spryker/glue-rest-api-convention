<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Bundles\GlueRestApiConvention\tests\SprykerTests\Glue\GlueRestApiConvention\RequestBuilder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestRestResourceBuilder;

class RequestRestResourceBuilderTest extends Unit
{
    /**
     * @return void
     */
    public function testEmptyUrl(): void
    {
        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setPath('');

        $builder = new RequestRestResourceBuilder();
        $result = $builder->build($glueRequest);

        $this->assertNull($result->getResource());
        $this->assertCount(0, $result->getParentResources());
    }

    /**
     * @return void
     */
    public function testNoResource(): void
    {
        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setPath('/');

        $builder = new RequestRestResourceBuilder();
        $result = $builder->build($glueRequest);

        $this->assertNull($result->getResource());
        $this->assertCount(0, $result->getParentResources());
    }

    /**
     * @return void
     */
    public function testSingleResource(): void
    {
        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setPath('/foo/foo-id');

        $builder = new RequestRestResourceBuilder();
        $result = $builder->build($glueRequest);

        $this->assertInstanceOf(GlueResourceTransfer::class, $result->getResource());
        $this->assertCount(0, $result->getParentResources());
        $this->assertSame('foo', $result->getResource()->getType());
        $this->assertSame('foo-id', $result->getResource()->getId());
    }

    /**
     * @return void
     */
    public function testSingleParentResource(): void
    {
        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setPath('/foo/foo-id/bar/bar-id');

        $builder = new RequestRestResourceBuilder();
        $result = $builder->build($glueRequest);

        $this->assertInstanceOf(GlueResourceTransfer::class, $result->getResource());
        $this->assertCount(1, $result->getParentResources());
        $this->assertSame('bar', $result->getResource()->getType());
        $this->assertSame('bar-id', $result->getResource()->getId());

        $parentResources = $result->getParentResources()->getArrayCopy();

        $this->assertArrayHasKey('foo', $parentResources);
        $this->assertSame('foo', $parentResources['foo']->getType());
        $this->assertSame('foo-id', $parentResources['foo']->getId());
    }

    /**
     * @return void
     */
    public function testSeveralParentResources(): void
    {
        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setPath('/foo/foo-id/bar/bar-id/baz/baz-id');

        $builder = new RequestRestResourceBuilder();
        $result = $builder->build($glueRequest);

        $this->assertInstanceOf(GlueResourceTransfer::class, $result->getResource());
        $this->assertCount(2, $result->getParentResources());
        $this->assertSame('baz', $result->getResource()->getType());
        $this->assertSame('baz-id', $result->getResource()->getId());

        $parentResources = $result->getParentResources()->getArrayCopy();

        $this->assertArrayHasKey('foo', $parentResources);
        $this->assertSame('foo', $parentResources['foo']->getType());
        $this->assertSame('foo-id', $parentResources['foo']->getId());

        $this->assertArrayHasKey('bar', $parentResources);
        $this->assertSame('bar', $parentResources['bar']->getType());
        $this->assertSame('bar-id', $parentResources['bar']->getId());
    }

    /**
     * @return void
     */
    public function testResourceWithoutId(): void
    {
        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setPath('/foo/');

        $builder = new RequestRestResourceBuilder();
        $result = $builder->build($glueRequest);

        $this->assertInstanceOf(GlueResourceTransfer::class, $result->getResource());
        $this->assertCount(0, $result->getParentResources());
        $this->assertSame('foo', $result->getResource()->getType());
        $this->assertNull($result->getResource()->getId());
    }
}
