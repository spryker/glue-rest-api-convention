<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTests\Glue\GlueRestApiConvention\RequestBuilder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GluePaginationTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestPaginationParameterBuilder;
use Spryker\Shared\GlueRestApiConvention\GlueRestApiConventionConstants;

class RequestPaginationParameterBuilderTest extends Unit
{
    /**
     * @return void
     */
    public function testNoPagination(): void
    {
        $glueRequest = new GlueRequestTransfer();
        $builder = new RequestPaginationParameterBuilder();
        $result = $builder->buildRequest($glueRequest);

        $this->assertNull($result->getPagination());
    }

    /**
     * @return void
     */
    public function testEmptyPagination(): void
    {
        $result = $this->buildRequest(null, null);
        $this->assertNull($result->getPagination());
    }

    /**
     * @return void
     */
    public function testMissingOffset(): void
    {
        $result = $this->buildRequest(null, 10);
        $this->assertNull($result->getPagination());
    }

    /**
     * @return void
     */
    public function testMissingLimit(): void
    {
        $result = $this->buildRequest(0, null);
        $this->assertNull($result->getPagination());
    }

    /**
     * @return void
     */
    public function testCompletePage(): void
    {
        $result = $this->buildRequest(0, 10);
        $this->assertInstanceOf(GluePaginationTransfer::class, $result->getPagination());
        $this->assertSame(0, $result->getPagination()->getOffset());
        $this->assertSame(10, $result->getPagination()->getLimit());
    }

    /**
     * @param int|null $offset
     * @param int|null $limit
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    protected function buildRequest(?int $offset = null, ?int $limit = null): GlueRequestTransfer
    {
        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setQueryFields([
            GlueRestApiConventionConstants::QUERY_PAGINATION => [
                GlueRestApiConventionConstants::PAGINATION_OFFSET => $offset,
                GlueRestApiConventionConstants::PAGINATION_LIMIT => $limit,
            ],
        ]);

        $builder = new RequestPaginationParameterBuilder();

        return $builder->buildRequest($glueRequest);
    }
}
