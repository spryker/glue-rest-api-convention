<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\Plugin;

use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResponseEncoderPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionFactory getFactory()
 */
class JsonResponseEncoderPlugin extends AbstractPlugin implements ResponseEncoderPluginInterface
{
    /**
     * @return array<string>
     */
    public function getAcceptedFormats(): array
    {
        return ['json'];
    }

    /**
     * @param mixed $content
     *
     * @return bool
     */
    public function accepts($content): bool
    {
        return true;
    }

    /**
     * @param mixed $content
     *
     * @return string
     */
    public function encode($content): string
    {
        return $this->getFactory()
            ->getEncodingService()
            ->encodeJson($content);
    }
}
