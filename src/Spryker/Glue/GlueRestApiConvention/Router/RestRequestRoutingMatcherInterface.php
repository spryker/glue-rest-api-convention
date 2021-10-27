<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\Router;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueRestApiConvention\Resource\ResourceInterface;

interface RestRequestRoutingMatcherInterface
{
    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param array<\Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResourceRoutePluginInterface> $resourceRoutePlugins
     *
     * @return \Spryker\Glue\GlueRestApiConvention\Resource\ResourceInterface
     */
    public function matchRequest(GlueRequestTransfer $glueRequestTransfer, array $resourceRoutePlugins): ResourceInterface;
}
