<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\RequestBuilder;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueSortingTransfer;
use Spryker\Shared\GlueRestApiConvention\GlueRestApiConventionConstants;

class RequestSortParameterBuilder
{
    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequest
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function buildRequest(GlueRequestTransfer $glueRequest): GlueRequestTransfer
    {
        $queryParameters = [];
        parse_str(parse_url($glueRequest->getPath(), PHP_URL_QUERY), $queryParameters);

        if (!isset($queryParameters[GlueRestApiConventionConstants::QUERY_SORT]) || empty($queryParameters[GlueRestApiConventionConstants::QUERY_SORT])) {
            return $glueRequest;
        }

        $sortFields = explode(',', $queryParameters[GlueRestApiConventionConstants::QUERY_SORT]);
        foreach ($sortFields as $field) {
            $isAscending = true;
            if ($field[0] === '-') {
                $isAscending = false;
                $field = trim($field, '-');
            }

            $glueRequest->addSorting(
                (new GlueSortingTransfer())
                    ->setField($field)
                    ->setIsAscending($isAscending)
            );
        }

        return $glueRequest;
    }
}
