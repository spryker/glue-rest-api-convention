<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTests\Glue\GlueRestApiConvention\RequestBuilder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueSortingTransfer;
use Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestSortParameterBuilder;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueHttp
 * @group GlueContext
 * @group GlueContextHttpExpanderTest
 * Add your own group annotations below this line
 */
class RequestSortParameterBuilderTest extends Unit
{
    /**
     * @var string
     */
    protected const FIRST_FIELD_NAME = 'field1';

    /**
     * @var string
     */
    protected const SECOND_FIELD_NAME = 'field2';

    /**
     * @var string
     */
    protected const URL_WITH_SORT_PARAMETER = '/foo/bar?sort=';

    /**
     * @return void
     */
    public function testNoSorting(): void
    {
        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setPath('/foo/bar');

        $builder = new RequestSortParameterBuilder();
        $result = $builder->buildRequest($glueRequest);

        $this->assertCount(0, $result->getSortings());
    }

    /**
     * @return void
     */
    public function testEmptySorting(): void
    {
        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setPath(static::URL_WITH_SORT_PARAMETER);

        $builder = new RequestSortParameterBuilder();
        $result = $builder->buildRequest($glueRequest);

        $this->assertCount(0, $result->getSortings());
    }

    /**
     * @return void
     */
    public function testAscendingSortField(): void
    {
        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setPath(static::URL_WITH_SORT_PARAMETER . static::FIRST_FIELD_NAME);

        $builder = new RequestSortParameterBuilder();
        $result = $builder->buildRequest($glueRequest);

        $this->assertCount(1, $result->getSortings());
        $firstSorting = $result->getSortings()->offsetGet(0);
        $this->assertInstanceOf(GlueSortingTransfer::class, $firstSorting);
        $this->assertSame(static::FIRST_FIELD_NAME, $firstSorting->getField());
        $this->assertTrue($firstSorting->getIsAscending());
    }

    /**
     * @return void
     */
    public function testDescendingSortField(): void
    {
        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setPath(static::URL_WITH_SORT_PARAMETER . '-' . static::FIRST_FIELD_NAME);

        $builder = new RequestSortParameterBuilder();
        $result = $builder->buildRequest($glueRequest);

        $this->assertCount(1, $result->getSortings());
        $firstSorting = $result->getSortings()->offsetGet(0);
        $this->assertInstanceOf(GlueSortingTransfer::class, $firstSorting);
        $this->assertSame(static::FIRST_FIELD_NAME, $firstSorting->getField());
        $this->assertFalse($firstSorting->getIsAscending());
    }

    /**
     * @return void
     */
    public function testMultipleAscendingSortingFields(): void
    {
        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setPath(static::URL_WITH_SORT_PARAMETER . implode(',', [static::FIRST_FIELD_NAME, static::SECOND_FIELD_NAME]));

        $builder = new RequestSortParameterBuilder();
        $result = $builder->buildRequest($glueRequest);

        $this->assertCount(2, $result->getSortings());
        $firstSorting = $result->getSortings()->offsetGet(0);
        $this->assertInstanceOf(GlueSortingTransfer::class, $firstSorting);
        $this->assertSame(static::FIRST_FIELD_NAME, $firstSorting->getField());
        $this->assertTrue($firstSorting->getIsAscending());

        $secondSorting = $result->getSortings()->offsetGet(1);
        $this->assertInstanceOf(GlueSortingTransfer::class, $secondSorting);
        $this->assertSame(static::SECOND_FIELD_NAME, $secondSorting->getField());
        $this->assertTrue($secondSorting->getIsAscending());
    }

    /**
     * @return void
     */
    public function testMultipleDescendingSortingFields(): void
    {
        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setPath(static::URL_WITH_SORT_PARAMETER . '-' . implode(',-', [static::FIRST_FIELD_NAME, static::SECOND_FIELD_NAME]));

        $builder = new RequestSortParameterBuilder();
        $result = $builder->buildRequest($glueRequest);

        $this->assertCount(2, $result->getSortings());
        $firstSorting = $result->getSortings()->offsetGet(0);
        $this->assertInstanceOf(GlueSortingTransfer::class, $firstSorting);
        $this->assertSame(static::FIRST_FIELD_NAME, $firstSorting->getField());
        $this->assertFalse($firstSorting->getIsAscending());

        $secondSorting = $result->getSortings()->offsetGet(1);
        $this->assertInstanceOf(GlueSortingTransfer::class, $secondSorting);
        $this->assertSame(static::SECOND_FIELD_NAME, $secondSorting->getField());
        $this->assertFalse($secondSorting->getIsAscending());
    }

    /**
     * @return void
     */
    public function testMultipleSortingFieldsWithDifferentDirections(): void
    {
        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setPath(static::URL_WITH_SORT_PARAMETER . '-' . implode(',', [static::FIRST_FIELD_NAME, static::SECOND_FIELD_NAME]));

        $builder = new RequestSortParameterBuilder();
        $result = $builder->buildRequest($glueRequest);

        $this->assertCount(2, $result->getSortings());
        $firstSorting = $result->getSortings()->offsetGet(0);
        $this->assertInstanceOf(GlueSortingTransfer::class, $firstSorting);
        $this->assertSame(static::FIRST_FIELD_NAME, $firstSorting->getField());
        $this->assertFalse($firstSorting->getIsAscending());

        $secondSorting = $result->getSortings()->offsetGet(1);
        $this->assertInstanceOf(GlueSortingTransfer::class, $secondSorting);
        $this->assertSame(static::SECOND_FIELD_NAME, $secondSorting->getField());
        $this->assertTrue($secondSorting->getIsAscending());
    }
}
