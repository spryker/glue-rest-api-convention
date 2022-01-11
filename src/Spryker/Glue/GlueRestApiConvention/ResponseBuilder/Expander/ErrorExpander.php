<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\ResponseBuilder\Expander;

use Generated\Shared\Transfer\GlueResponseTransfer;

class ErrorExpander implements ErrorExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     * @param array $data
     *
     * @return array
     */
    public function expandResponseData(GlueResponseTransfer $glueResponseTransfer, array $data): array
    {
        if (!$glueResponseTransfer->getErrors()->count()) {
            return $data;
        }

        foreach ($glueResponseTransfer->getErrors() as $glueErrorTransfer) {
            $data += $glueErrorTransfer->toArray(true, true);
        }

        return $data;
    }
}
