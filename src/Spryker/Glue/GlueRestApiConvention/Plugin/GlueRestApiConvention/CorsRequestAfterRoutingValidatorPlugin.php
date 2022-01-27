<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\RequestAfterRoutingValidatorPluginInterface;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\RestResourceInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionFactory getFactory()
 */
class CorsRequestAfterRoutingValidatorPlugin extends AbstractPlugin implements RequestAfterRoutingValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Validates that the `access-control-request-method` header is present and allowed for the resource.
     * - Validates that the `access-control-request-headers` header is present and is allowed in the `\Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionConfig::getCorsAllowedHeaders()`.
     * - Does nothing if the method used by the request is not OPTIONS.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\RestResourceInterface $restResource
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    public function validateRequest(GlueRequestTransfer $glueRequestTransfer, RestResourceInterface $restResource): GlueRequestValidationTransfer
    {
        return $this->getFactory()->createRequestCorsValidator()->validate(
            $glueRequestTransfer,
            $restResource,
        );
    }
}
