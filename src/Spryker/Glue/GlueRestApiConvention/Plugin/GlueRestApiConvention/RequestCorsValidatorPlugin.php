<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\RequestAfterRoutingValidatorPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionFactory getFactory()
 */
class RequestCorsValidatorPlugin extends AbstractPlugin implements RequestAfterRoutingValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResourceRoutePluginInterface $resourceRoutePlugin
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    public function validateRequest(GlueRequestTransfer $glueRequestTransfer, ResourceRoutePluginInterface $resourceRoutePlugin): GlueRequestValidationTransfer
    {
        return $this->getFactory()->createRequestCorsValidator()->validate(
            $glueRequestTransfer,
            $resourceRoutePlugin,
        );
    }
}
