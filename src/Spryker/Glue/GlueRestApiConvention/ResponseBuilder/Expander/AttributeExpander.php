<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\ResponseBuilder\Expander;

use Generated\Shared\Transfer\GlueResponseTransfer;

class AttributeExpander implements AttributeExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponse
     * @param array $data
     *
     * @return array
     */
    public function expandResponseData(GlueResponseTransfer $glueResponse, array $data): array
    {
        if ($glueResponse->getAttributes()) {
            $data += $glueResponse->getAttributes()->toArray(true, true);
        }

        return $data;
    }
}
