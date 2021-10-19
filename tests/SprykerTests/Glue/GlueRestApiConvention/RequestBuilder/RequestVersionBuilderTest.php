<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTests\Glue\GlueRestApiConvention\RequestBuilder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueVersionTransfer;
use Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestVersionBuilder;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueRestApiConvention
 * @group RequestBuilder
 * @group RequestVersionBuilderTest
 * Add your own group annotations below this line
 */
class RequestVersionBuilderTest extends Unit
{
    /**
     * @return void
     */
    public function testNoVersion(): void
    {
        $contentType = 'application/json';
        $result = $this->buildRequest($contentType);

        $this->assertNull($result->getVersion());
    }

    /**
     * @return void
     */
    public function testOnlyMajorVersion(): void
    {
        $contentType = 'application/json; version=1';
        $result = $this->buildRequest($contentType);

        $this->assertInstanceOf(GlueVersionTransfer::class, $result->getVersion());
        $this->assertSame(1, $result->getVersion()->getMajor());
        $this->assertSame(0, $result->getVersion()->getMinor());
    }

    /**
     * @return void
     */
    public function testNonNumericVersion(): void
    {
        $contentType = 'application/json; version=foo';

        $result = $this->buildRequest($contentType);

        $this->assertNull($result->getVersion());
    }

    /**
     * @return void
     */
    public function testFullVersion(): void
    {
        $contentType = 'application/json; version=1.0';

        $result = $this->buildRequest($contentType);

        $this->assertInstanceOf(GlueVersionTransfer::class, $result->getVersion());
        $this->assertSame(1, $result->getVersion()->getMajor());
        $this->assertSame(0, $result->getVersion()->getMinor());
    }

    /**
     * @return void
     */
    public function testPatchVersion(): void
    {
        $contentType = 'application/json; version=1.0.3';

        $result = $this->buildRequest($contentType);

        $this->assertInstanceOf(GlueVersionTransfer::class, $result->getVersion());
        $this->assertSame(1, $result->getVersion()->getMajor());
        $this->assertSame(0, $result->getVersion()->getMinor());
    }

    /**
     * @return void
     */
    public function testDifferentMajorVersion(): void
    {
        $contentType = 'application/json; version=2.0';

        $result = $this->buildRequest($contentType);

        $this->assertInstanceOf(GlueVersionTransfer::class, $result->getVersion());
        $this->assertSame(2, $result->getVersion()->getMajor());
        $this->assertSame(0, $result->getVersion()->getMinor());
    }

    /**
     * @return void
     */
    public function testDifferentMinorVersion(): void
    {
        $contentType = 'application/json; version=3.2';

        $result = $this->buildRequest($contentType);

        $this->assertInstanceOf(GlueVersionTransfer::class, $result->getVersion());
        $this->assertSame(3, $result->getVersion()->getMajor());
        $this->assertSame(2, $result->getVersion()->getMinor());
    }

    /**
     * @return void
     */
    public function testHighVersion(): void
    {
        $contentType = 'application/json; version=1234567.2345678';

        $result = $this->buildRequest($contentType);

        $this->assertInstanceOf(GlueVersionTransfer::class, $result->getVersion());
        $this->assertSame(1234567, $result->getVersion()->getMajor());
        $this->assertSame(2345678, $result->getVersion()->getMinor());
    }

    /**
     * @param string $contentType
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    protected function buildRequest(string $contentType): GlueRequestTransfer
    {
        $glueRequest = new GlueRequestTransfer();
        $glueRequest->addMeta('content-type', $contentType);
        $builder = new RequestVersionBuilder();

        return $builder->build($glueRequest);
    }
}
