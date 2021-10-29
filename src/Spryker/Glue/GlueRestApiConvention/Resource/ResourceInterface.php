<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\Resource;

use Spryker\Glue\GlueApplication\Resource\ResourceInterface as GlueApplicationResourceInterface;

interface ResourceInterface extends GlueApplicationResourceInterface
{
    /**
     * Specification:
     * - retrieve the configured HTTP to action mappings for this resource
     *
     * @api
     *
     * @return \Spryker\Glue\GlueRestApiConvention\Resource\ResourceRouteCollectionInterface|null
     */
    public function getMatchingResourceCollection(): ?ResourceRouteCollectionInterface;
}
