<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Bundles\GlueRestApiConvention\tests\SprykerTests\Glue\GlueRestApiConvention\RequestBuilder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestQueryParameterBuilder;

class RequestQueryParameterBuilderTest extends Unit
{
    /**
     * @var string
     */
    protected const FIRST_FIELD_NAME = 'field1';
    /**
     * @var string
     */
    protected const FIRST_FIELD_VALUE = 'value1';
    /**
     * @var string
     */
    protected const SECOND_FIELD_NAME = 'field2';
    /**
     * @var string
     */
    protected const SECOND_FIELD_VALUE = 'value2';
    /**
     * @var string
     */
    protected const THIRD_FIELD_VALUE = 'value3';

    /**
     * @return void
     */
    public function testNoQueryFields(): void
    {
        $queryParameters = [];
        $buildRequest = $this->buildRequest($queryParameters);
        $this->assertCount(0, $buildRequest->getQueryFields());
    }

    /**
     * @return void
     */
    public function testEmptyQueryFields(): void
    {
        $queryParameters = [static::FIRST_FIELD_NAME => ''];
        $buildRequest = $this->buildRequest($queryParameters);
        $this->assertCount(1, $buildRequest->getQueryFields());
        $this->assertArrayHasKey(static::FIRST_FIELD_NAME, $buildRequest->getQueryFields());
        $this->assertEmpty($buildRequest->getQueryFields()[static::FIRST_FIELD_NAME]);
    }

    /**
     * @return void
     */
    public function testSingleQueryField(): void
    {
        $queryParameters = [static::FIRST_FIELD_NAME => static::FIRST_FIELD_VALUE];
        $buildRequest = $this->buildRequest($queryParameters);
        $this->assertCount(1, $buildRequest->getQueryFields());
        $this->assertArrayHasKey(static::FIRST_FIELD_NAME, $buildRequest->getQueryFields());
        $this->assertSame(static::FIRST_FIELD_VALUE, $buildRequest->getQueryFields()[static::FIRST_FIELD_NAME]);
    }

    /**
     * @return void
     */
    public function testMultipleQueryFields(): void
    {
        $queryParameters = [
            static::FIRST_FIELD_NAME => static::FIRST_FIELD_VALUE,
            static::SECOND_FIELD_NAME => static::SECOND_FIELD_VALUE,
        ];
        $buildRequest = $this->buildRequest($queryParameters);
        $this->assertCount(2, $buildRequest->getQueryFields());
        $this->assertArrayHasKey(static::FIRST_FIELD_NAME, $buildRequest->getQueryFields());
        $this->assertSame(static::FIRST_FIELD_VALUE, $buildRequest->getQueryFields()[static::FIRST_FIELD_NAME]);
        $this->assertArrayHasKey(static::SECOND_FIELD_NAME, $buildRequest->getQueryFields());
        $this->assertSame(static::SECOND_FIELD_VALUE, $buildRequest->getQueryFields()[static::SECOND_FIELD_NAME]);
    }

    /**
     * @return void
     */
    public function testMultiValueFields(): void
    {
        $queryParameters = [
            static::FIRST_FIELD_NAME => static::FIRST_FIELD_VALUE,
            static::SECOND_FIELD_NAME => [static::SECOND_FIELD_VALUE, static::THIRD_FIELD_VALUE],
        ];
        $buildRequest = $this->buildRequest($queryParameters);
        $this->assertCount(2, $buildRequest->getQueryFields());
        $this->assertArrayHasKey(static::FIRST_FIELD_NAME, $buildRequest->getQueryFields());
        $this->assertSame(static::FIRST_FIELD_VALUE, $buildRequest->getQueryFields()[static::FIRST_FIELD_NAME]);
        $this->assertArrayHasKey(static::SECOND_FIELD_NAME, $buildRequest->getQueryFields());
        $this->assertIsArray($buildRequest->getQueryFields()[static::SECOND_FIELD_NAME]);
        $this->assertCount(2, $buildRequest->getQueryFields()[static::SECOND_FIELD_NAME]);
        $this->assertSame(static::SECOND_FIELD_VALUE, $buildRequest->getQueryFields()[static::SECOND_FIELD_NAME][0]);
        $this->assertSame(static::THIRD_FIELD_VALUE, $buildRequest->getQueryFields()[static::SECOND_FIELD_NAME][1]);
    }

    /**
     * @param array $queryParameters
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    protected function buildRequest(array $queryParameters): GlueRequestTransfer
    {
        $url = '/foo/bar?' . http_build_query($queryParameters);
        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setPath($url);
        $requestBuilder = new RequestQueryParameterBuilder();

        return $requestBuilder->buildRequest($glueRequest);
    }
}
