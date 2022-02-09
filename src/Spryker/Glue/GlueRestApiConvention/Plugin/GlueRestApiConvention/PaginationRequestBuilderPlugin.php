<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\RequestBuilderPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionFactory getFactory()
 */
class PaginationRequestBuilderPlugin extends AbstractPlugin implements RequestBuilderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Extracts pagination parameters into `GlueRequestTransfer.pagination`.
     * - Uses `page[offset]` and `page[limit]` query parameter.
     * - If above parameters are not among the query parameters, does nothing.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function build(GlueRequestTransfer $glueRequestTransfer): GlueRequestTransfer
    {
        return $this->getFactory()->createRequestPaginationParameterBuilder()->buildRequest($glueRequestTransfer);
    }
}
