<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\ResponseBuilder;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResponseEncoderPluginInterface;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResponseExpanderPluginInterface;

interface ResponseContentBuilderInterface
{
    /**
     * @param \Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResponseEncoderPluginInterface $responseEncoder
     *
     * @return $this
     */
    public function addResponseEncoderPlugin(ResponseEncoderPluginInterface $responseEncoder);

    /**
     * @param \Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResponseExpanderPluginInterface $responseExpanderPlugin
     *
     * @return $this
     */
    public function addResponseExpanderPlugin(ResponseExpanderPluginInterface $responseExpanderPlugin);

    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponse
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequest
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function buildResponse(GlueResponseTransfer $glueResponse, GlueRequestTransfer $glueRequest): GlueResponseTransfer;
}
