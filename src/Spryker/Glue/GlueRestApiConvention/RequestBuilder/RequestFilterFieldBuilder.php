<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\RequestBuilder;

use Generated\Shared\Transfer\GlueFilterTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;

class RequestFilterFieldBuilder implements RequestBuilderInterface
{
    /**
     * @var string
     */
    protected const QUERY_FILTER = 'filter';

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function buildRequest(GlueRequestTransfer $glueRequestTransfer): GlueRequestTransfer
    {
        $queryParameters = $glueRequestTransfer->getQueryFields();

        if (!isset($queryParameters[static::QUERY_FILTER]) || !is_array($queryParameters[static::QUERY_FILTER])) {
            return $glueRequestTransfer;
        }

        foreach ($queryParameters[static::QUERY_FILTER] as $key => $value) {
            [$resource, $field] = explode('.', $key);
            $glueRequestTransfer->addFilter(
                (new GlueFilterTransfer())
                    ->setResource($resource)
                    ->setField($field)
                    ->setValue($value),
            );
        }

        return $glueRequestTransfer;
    }
}
