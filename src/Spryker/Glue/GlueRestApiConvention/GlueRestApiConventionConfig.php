<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention;

use Spryker\Glue\GlueRestApiConvention\Cors\CorsConstants;
use Spryker\Glue\Kernel\AbstractBundleConfig;

class GlueRestApiConventionConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const CONVENTION_REST_API = 'rest_api';

    /**
     * Specification:
     *  - List of allowed CORS headers.
     *
     * @api
     *
     * @return array<string>
     */
    public function getCorsAllowedHeaders(): array
    {
        return [
            CorsConstants::HEADER_ACCEPT,
            CorsConstants::HEADER_CONTENT_TYPE,
            CorsConstants::HEADER_CONTENT_LANGUAGE,
            CorsConstants::HEADER_ACCEPT_LANGUAGE,
            CorsConstants::HEADER_AUTHORIZATION,
        ];
    }
}
