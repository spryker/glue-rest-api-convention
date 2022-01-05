<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResourceExecutorInterface;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\RestResourceInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionFactory getFactory()
 */
// TODO: will be removed when GlueApplication will be done
class RestApiResourceExecutorPlugin extends AbstractPlugin implements ResourceExecutorInterface
{
    /**
     * {@inheritDoc}
     * - Transform given resource into a response.
     *
     * @api
     *
     * @param \Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\RestResourceInterface $resource
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function execute(RestResourceInterface $resource, GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        return $this->getFactory()->createRestApiResourceExecutor()->executeResource(
            $resource,
            $glueRequestTransfer,
        );
    }
}
