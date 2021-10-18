<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GlueRestApiConvention;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface GlueRestApiConventionConstants
{
    /**
     * @var string
     */
    public const QUERY_SORT = 'sort';
    /**
     * @var string
     */
    public const QUERY_PAGINATION = 'page';
    /**
     * @var string
     */
    public const PAGINATION_OFFSET = 'offset';
    /**
     * @var string
     */
    public const PAGINATION_LIMIT = 'limit';
}
