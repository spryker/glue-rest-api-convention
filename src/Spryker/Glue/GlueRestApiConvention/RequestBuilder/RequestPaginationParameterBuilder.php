<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\RequestBuilder;

use Generated\Shared\Transfer\GluePaginationTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Shared\GlueRestApiConvention\GlueRestApiConventionConstants;

class RequestPaginationParameterBuilder implements RequestPaginationParameterBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequest
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function buildRequest(GlueRequestTransfer $glueRequest): GlueRequestTransfer
    {
        $queryParameters = $glueRequest->getQueryFields();

        if (!isset($queryParameters[GlueRestApiConventionConstants::QUERY_PAGINATION])) {
            return $glueRequest->setPagination(null);
        }

        $page = $queryParameters[GlueRestApiConventionConstants::QUERY_PAGINATION];

        if (isset($page[GlueRestApiConventionConstants::PAGINATION_OFFSET], $page[GlueRestApiConventionConstants::PAGINATION_LIMIT])) {
            $glueRequest->setPagination(
                (new GluePaginationTransfer())
                    ->setOffset($page[GlueRestApiConventionConstants::PAGINATION_OFFSET])
                    ->setLimit($page[GlueRestApiConventionConstants::PAGINATION_LIMIT])
            );
        }

        return $glueRequest;
    }
}
