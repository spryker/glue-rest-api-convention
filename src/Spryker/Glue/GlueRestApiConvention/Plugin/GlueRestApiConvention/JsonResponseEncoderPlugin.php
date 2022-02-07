<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResponseEncoderPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionFactory getFactory()
 */
class JsonResponseEncoderPlugin extends AbstractPlugin implements ResponseEncoderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Return all formats that mean the JSON encoder can be used.
     *
     * @api
     *
     * @return array<string>
     */
    public function getAcceptedFormats(): array
    {
        return ['application/json'];
    }

    /**
     * {@inheritDoc}
     * - Check if the given content can be encoded by this implementation.
     * - Always returns true, is the default encoder.
     *
     * @api
     *
     * @param mixed $content
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return bool
     */
    public function accepts($content, GlueRequestTransfer $glueRequestTransfer): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     * - Transform given content into JSON format.
     *
     * @api
     *
     * @param mixed $content
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     *
     * @return string
     */
    public function encode($content, GlueResponseTransfer $glueResponseTransfer): string
    {
        return $this->getFactory()
            ->getUtilEncodingService()
            ->encodeJson($content);
    }
}
