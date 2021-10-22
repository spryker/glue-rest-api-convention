<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueRestApiConvention\RequestBuilder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueSortingTransfer;
use Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestSortParameterBuilder;
use Spryker\Shared\GlueRestApiConvention\GlueRestApiConventionConstants;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueRestApiConvention
 * @group RequestBuilder
 * @group RequestSortParameterBuilderTest
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
    public function testEmptySorting(): void
    {
        $result = $this->buildRequest([]);

        $this->assertCount(0, $result->getSortings());
    }

    /**
     * @return void
     */
    public function testAscendingSortField(): void
    {
        $result = $this->buildRequest([static::FIRST_FIELD_NAME]);

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
        $result = $this->buildRequest(['-' . static::FIRST_FIELD_NAME]);

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
        $result = $this->buildRequest([static::FIRST_FIELD_NAME, static::SECOND_FIELD_NAME]);

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
        $result = $this->buildRequest(['-' . static::FIRST_FIELD_NAME, '-' . static::SECOND_FIELD_NAME]);

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
        $result = $this->buildRequest(['-' . static::FIRST_FIELD_NAME, static::SECOND_FIELD_NAME]);

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

    /**
     * @param array $sorting
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    protected function buildRequest(array $sorting = []): GlueRequestTransfer
    {
        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setQueryFields([GlueRestApiConventionConstants::QUERY_SORT => implode(',', $sorting)]);

        $builder = new RequestSortParameterBuilder();

        return $builder->buildRequest($glueRequest);
    }
}
