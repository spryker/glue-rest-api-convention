<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\RequestValidator;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;

class RequestPaginationValidator implements RequestPaginationValidatorInterface
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_INVALID_PAGE_PARAMETERS = 'Pagination parameters are invalid.';

    /**
     * @var string
     */
    protected const ERROR_STATUS_CODE = '400';

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequest
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    public function validate(GlueRequestTransfer $glueRequest): GlueRequestValidationTransfer
    {
        if ($glueRequest->getPagination() === null) {
            return (new GlueRequestValidationTransfer())->setIsValid(true);
        }

        $offset = $glueRequest->getPagination()->getOffset();
        $limit = $glueRequest->getPagination()->getLimit();

        if (!is_numeric($offset) || !is_numeric($limit)) {
            return $this->buildInvalidValidationResult();
        }

        if ($limit <= 0) {
            return $this->buildInvalidValidationResult();
        }

        return (new GlueRequestValidationTransfer())->setIsValid(true);
    }

    /**
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    protected function buildInvalidValidationResult(): GlueRequestValidationTransfer
    {
        return (new GlueRequestValidationTransfer())
            ->setIsValid(false)
            ->setValidationError(static::ERROR_MESSAGE_INVALID_PAGE_PARAMETERS)
            ->setStatusCode(static::ERROR_STATUS_CODE);
    }
}
