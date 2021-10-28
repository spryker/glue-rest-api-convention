<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\Resource;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;

class ResourceExecutor implements ResourceExecutorInterface
{
    /**
     * @param \Spryker\Glue\GlueRestApiConvention\Resource\ResourceInterface $resource
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function executeResource(ResourceInterface $resource, GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        if ($glueRequestTransfer->getResource() && $glueRequestTransfer->getResource()->getId()) {
            $resourceId = $glueRequestTransfer->getResource()->getId();

            return call_user_func($resource->getResource(), $resourceId, $glueRequestTransfer);
        }

        return call_user_func($resource->getResource(), $glueRequestTransfer);
    }
}
