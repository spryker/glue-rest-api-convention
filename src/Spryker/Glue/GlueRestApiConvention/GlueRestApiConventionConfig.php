<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class GlueRestApiConventionConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const CONVENTION_REST_API = 'rest_api';

    /**
     * @var string
     */
    protected const HEADER_ACCEPT = 'accept';

    /**
     * @var string
     */
    protected const HEADER_ACCEPT_LANGUAGE = 'accept-language';

    /**
     * @var string
     */
    protected const HEADER_CONTENT_TYPE = 'content-type';

    /**
     * @var string
     */
    protected const HEADER_CONTENT_LANGUAGE = 'content-language';

    /**
     * @var string
     */
    protected const HEADER_AUTHORIZATION = 'authorization';

    /**
     * Specification:
     * - List of allowed CORS headers.
     *
     * @api
     *
     * @return array<string>
     */
    public function getCorsAllowedHeaders(): array
    {
        return [
            static::HEADER_ACCEPT,
            static::HEADER_CONTENT_TYPE,
            static::HEADER_CONTENT_LANGUAGE,
            static::HEADER_ACCEPT_LANGUAGE,
            static::HEADER_AUTHORIZATION,
        ];
    }
}
