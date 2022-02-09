<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\ResponseBuilder\Expander;

use ArrayObject;
use Generated\Shared\Transfer\GlueResponseTransfer;

class AttributeExpander implements AttributeExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     * @param array $data
     *
     * @return array
     */
    public function expandResponseData(GlueResponseTransfer $glueResponseTransfer, array $data): array
    {
        if ($glueResponseTransfer->getResources()->count() !== 0) {
            foreach ($glueResponseTransfer->getResources() as $resource) {
                $data += $resource->getAttributes()->toArray(true, true);
            }
        }

        return $data;
    }
}
