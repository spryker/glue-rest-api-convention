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
use stdClass;

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
     * @param array<\Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResponseEncoderPluginInterface> $responseEncoderPlugins
     * @param array<\Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResponseExpanderPluginInterface> $responseExpanderPlugins
     */
    public function __construct(
        array $responseEncoderPlugins,
        array $responseExpanderPlugins
    ) {
        array_map(function (ResponseEncoderPluginInterface $responseEncoderPlugin): void {
            $this->addResponseEncoderPlugin($responseEncoderPlugin);
        }, $responseEncoderPlugins);
        array_map(function (ResponseExpanderPluginInterface $responseExpanderPlugin): void {
            $this->addResponseExpanderPlugin($responseExpanderPlugin);
        }, $responseExpanderPlugins);
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
     * @param \Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResponseExpanderPluginInterface $responseExpanderPlugin
     *
     * @return $this
     */
    public function addResponseExpanderPlugin(ResponseExpanderPluginInterface $responseExpanderPlugin)
    {
        $this->responseExpanders[] = $responseExpanderPlugin;

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponse
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequest
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function buildResponse(GlueResponseTransfer $glueResponse, GlueRequestTransfer $glueRequest): GlueResponseTransfer
    {
        if (!$glueResponse->getStatus()) {
            $glueResponse->setStatus('200');
        }

        if ($glueResponse->getContent()) {
            return $glueResponse;
        }

        $format = $glueRequest->getAcceptedFormat();

        if (!$format || !array_key_exists($format, $this->responseEncoders)) {
            $glueResponse->setStatus('400');
            $glueResponse->setContent('invalid format: ' . $format);

            return $glueResponse;
        }

        $data = [];

        foreach ($this->responseExpanders as $responseExpander) {
            $data = $responseExpander->expand($glueResponse, $glueRequest, $data);
        }

        return $this->formatResponse($format, $data, $glueResponse);
    }

    /**
     * @param string $format
     * @param array $data
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponse
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    protected function formatResponse(string $format, array $data, GlueResponseTransfer $glueResponse): GlueResponseTransfer
    {
        foreach ($this->responseEncoders[$format] as $responseEncoder) {
            if (!$responseEncoder->accepts($data)) {
                continue;
            }

            if (empty($data)) {
                $data = new stdClass();
            }

            $glueResponse->setContent($responseEncoder->encode($data));

            return $glueResponse;
        }

        $glueResponse->setStatus('500');
        $glueResponse->setContent('Missing encoder for ' . $format);

        return $glueResponse;
    }
}
