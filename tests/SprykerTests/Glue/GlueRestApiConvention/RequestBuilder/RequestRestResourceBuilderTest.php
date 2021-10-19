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
        $path = '';
        $result = $this->buildRequest($path);

        $this->assertNull($result->getResource());
        $this->assertCount(0, $result->getParentResources());
    }

    /**
     * @return void
     */
    public function testNoResource(): void
    {
        $path = '/';
        $result = $this->buildRequest($path);

        $this->assertNull($result->getResource());
        $this->assertCount(0, $result->getParentResources());
    }

    /**
     * @return void
     */
    public function testSingleResource(): void
    {
        $path = '/foo/foo-id';
        $result = $this->buildRequest($path);

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
        $path = '/foo/foo-id/bar/bar-id';
        $result = $this->buildRequest($path);

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
        $path = '/foo/foo-id/bar/bar-id/baz/baz-id';
        $result = $this->buildRequest($path);

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
        $path = '/foo/';
        $result = $this->buildRequest($path);

        $this->assertInstanceOf(GlueResourceTransfer::class, $result->getResource());
        $this->assertCount(0, $result->getParentResources());
        $this->assertSame('foo', $result->getResource()->getType());
        $this->assertNull($result->getResource()->getId());
    }

    /**
     * @param string $path
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    protected function buildRequest(string $path): GlueRequestTransfer
    {
        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setPath($path);

        $builder = new RequestRestResourceBuilder();

        return $builder->build($glueRequest);
    }
}
