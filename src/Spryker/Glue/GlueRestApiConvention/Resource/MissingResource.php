<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\Resource;

use Spryker\Glue\GlueApplication\Resource\MissingResource as GlueApplicationMissingResource;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Resource\MissingResourceInterface;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Resource\ResourceRouteCollectionInterface;

class MissingResource extends GlueApplicationMissingResource implements MissingResourceInterface
{
    /**
     * @return \Spryker\Glue\GlueRestApiConventionExtension\Dependency\Resource\ResourceRouteCollectionInterface|null
     */
    public function getMatchingResourceCollection(): ?ResourceRouteCollectionInterface
    {
        return null;
    }
}
