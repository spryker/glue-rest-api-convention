<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\ResponseBuilder;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionConfig;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResponseEncoderPluginInterface;
use stdClass;
use Symfony\Component\HttpFoundation\Response;

class ResponseContentBuilder implements ResponseContentBuilderInterface
{
    /**
     * @var array<string, array<\Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResponseEncoderPluginInterface>>
     */
    protected array $responseEncoders = [];

    /**
     * @var array<\Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResponseExpanderPluginInterface>
     */
    protected array $responseExpanders = [];

    /**
     * @var \Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionConfig
     */
    protected GlueRestApiConventionConfig $glueRestApiConventionConfig;

    /**
     * @param array $responseEncoderPlugins
     * @param array $responseExpanderPlugins
     * @param \Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionConfig $glueRestApiConventionConfig
     */
    public function __construct(
        array $responseEncoderPlugins,
        array $responseExpanderPlugins,
        GlueRestApiConventionConfig $glueRestApiConventionConfig
    ) {
        array_map(function (ResponseEncoderPluginInterface $responseEncoderPlugin): void {
            $this->addResponseEncoderPlugin($responseEncoderPlugin);
        }, $responseEncoderPlugins);
        $this->responseExpanders = $responseExpanderPlugins;
        $this->glueRestApiConventionConfig = $glueRestApiConventionConfig;
    }

    /**
     * @param \Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResponseEncoderPluginInterface $responseEncoder
     *
     * @return $this
     */
    public function addResponseEncoderPlugin(ResponseEncoderPluginInterface $responseEncoder)
    {
        foreach ($responseEncoder->getAcceptedFormats() as $acceptedFormat) {
            if (!isset($this->responseEncoders[$acceptedFormat])) {
                $this->responseEncoders[$acceptedFormat] = [];
            }

            $this->responseEncoders[$acceptedFormat][] = $responseEncoder;
        }

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function buildResponse(
        GlueResponseTransfer $glueResponseTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        if (!$glueResponseTransfer->getStatus()) {
            $glueResponseTransfer->setStatus((string)Response::HTTP_OK);
        }

        if ($glueResponseTransfer->getContent()) {
            return $glueResponseTransfer;
        }

        if (!array_key_exists($glueRequestTransfer->getAcceptedFormat(), $this->responseEncoders)) {
            $glueRequestTransfer->setAcceptedFormat(
                $this->glueRestApiConventionConfig->getDefaultFormat(),
            );
        }

        $data = [];

        foreach ($this->responseExpanders as $responseExpander) {
            $data = $responseExpander->expand($glueResponseTransfer, $glueRequestTransfer, $data);
        }

        return $this->formatResponse($glueRequestTransfer->getAcceptedFormat(), $data, $glueResponseTransfer, $glueRequestTransfer);
    }

    /**
     * @param string $format
     * @param array $data
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    protected function formatResponse(
        string $format,
        array $data,
        GlueResponseTransfer $glueResponseTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        foreach ($this->responseEncoders[$format] as $responseEncoder) {
            if (!$responseEncoder->accepts($data, $glueRequestTransfer)) {
                continue;
            }

            if (!$data) {
                $data = new stdClass();
            }

            $glueResponseTransfer->setContent($responseEncoder->encode($data, $glueResponseTransfer));
            $glueResponseTransfer->addMeta('Content-Type', 'application/json');

            return $glueResponseTransfer;
        }

        return $glueResponseTransfer;
    }
}
