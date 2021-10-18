<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\RequestBuilder;

use Generated\Shared\Transfer\GlueRequestTransfer;

class RequestQueryParameterBuilder
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

        $glueRequest->setQueryFields($queryParameters);

        return $glueRequest;
    }
}
