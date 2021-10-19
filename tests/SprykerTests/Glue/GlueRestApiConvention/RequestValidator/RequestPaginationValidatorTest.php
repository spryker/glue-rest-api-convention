<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTests\Glue\GlueRestApiConvention\RequestValidator;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GluePaginationTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Spryker\Glue\GlueRestApiConvention\RequestValidator\RequestPaginationValidator;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueRestApiConvention
 * @group RequestValidator
 * @group RequestPaginationValidatorTest
 * Add your own group annotations below this line
 */
class RequestPaginationValidatorTest extends Unit
{
    /**
     * @return void
     */
    public function testNoPagination(): void
    {
        $pagination = null;
        $result = $this->validatePagination($pagination);

        $this->assertInstanceOf(GlueRequestValidationTransfer::class, $result);
        $this->assertTrue($result->getIsValid());
        $this->assertEmpty($result->getValidationError());
        $this->assertEmpty($result->getStatusCode());
    }

    /**
     * @return void
     */
    public function testPaginationWithMissingOffset(): void
    {
        $pagination = (new GluePaginationTransfer())->setLimit(10)->setOffset(null);
        $result = $this->validatePagination($pagination);

        $this->assertInstanceOf(GlueRequestValidationTransfer::class, $result);
        $this->assertFalse($result->getIsValid());
        $this->assertSame('Pagination parameters are invalid.', $result->getValidationError());
        $this->assertSame('400', $result->getStatusCode());
    }

    /**
     * @return void
     */
    public function testPaginationWithMissingLimit(): void
    {
        $pagination = (new GluePaginationTransfer())->setLimit(null)->setOffset(0);
        $result = $this->validatePagination($pagination);

        $this->assertInstanceOf(GlueRequestValidationTransfer::class, $result);
        $this->assertFalse($result->getIsValid());
        $this->assertSame('Pagination parameters are invalid.', $result->getValidationError());
        $this->assertSame('400', $result->getStatusCode());
    }

    /**
     * @return void
     */
    public function testPaginationWithZeroLimit(): void
    {
        $pagination = (new GluePaginationTransfer())->setLimit(0)->setOffset(0);
        $result = $this->validatePagination($pagination);

        $this->assertInstanceOf(GlueRequestValidationTransfer::class, $result);
        $this->assertFalse($result->getIsValid());
        $this->assertSame('Pagination parameters are invalid.', $result->getValidationError());
        $this->assertSame('400', $result->getStatusCode());
    }

    /**
     * @return void
     */
    public function testPagination(): void
    {
        $pagination = (new GluePaginationTransfer())->setLimit(10)->setOffset(0);
        $result = $this->validatePagination($pagination);

        $this->assertInstanceOf(GlueRequestValidationTransfer::class, $result);
        $this->assertTrue($result->getIsValid());
        $this->assertEmpty($result->getValidationError());
        $this->assertEmpty($result->getStatusCode());
    }

    /**
     * @param \Generated\Shared\Transfer\GluePaginationTransfer|null $pagination
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    protected function validatePagination(?GluePaginationTransfer $pagination): GlueRequestValidationTransfer
    {
        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setPagination($pagination);
        $validator = new RequestPaginationValidator();

        return $validator->validate($glueRequest);
    }
}
