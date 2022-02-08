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
    public const UNSUPPORTED_ACCEPT_FORMAT = '010';

    /**
     * @var string
     */
    public const UNSUPPORTED_ACCEPT_FORMAT_MESSAGE = 'Unsupported "Accept" format used.';

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
     * @var string
     */
    protected const DEFAULT_FORMAT = 'application/json';

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

    /**
     * Specification:
     * - Returns the default format REST API will use if none could be negotiated.
     *
     * @api
     *
     * @return string
     */
    public function getDefaultFormat(): string
    {
        return static::DEFAULT_FORMAT;
    }
}
