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

        $formats = array_flip($glueRequestTransfer->getAcceptedFormats());
        $usedFormats = array_keys(array_intersect_key($formats, $this->responseEncoders));

        if (!$formats || !$usedFormats) {
            $glueResponseTransfer->setStatus((string)Response::HTTP_BAD_REQUEST);
            $glueResponseTransfer->setContent('invalid formats: ' . $formats);

            return $glueResponseTransfer;
        }

        $data = [];

        foreach ($this->responseExpanders as $responseExpander) {
            $data = $responseExpander->expand($glueResponseTransfer, $glueRequestTransfer, $data);
        }

        return $this->formatResponse($usedFormats[0], $data, $glueResponseTransfer);
    }

    /**
     * @param string $format
     * @param array $data
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    protected function formatResponse(string $format, array $data, GlueResponseTransfer $glueResponseTransfer): GlueResponseTransfer
    {
        foreach ($this->responseEncoders[$format] as $responseEncoder) {
            if (!$responseEncoder->accepts($data)) {
                continue;
            }

            if (!$data) {
                $data = new stdClass();
            }

            $glueResponseTransfer->setContent($responseEncoder->encode($data));
            $glueResponseTransfer->addMeta('Content-Type', 'application/json');

            return $glueResponseTransfer;
        }

        $glueResponseTransfer->addMeta('Content-Type', '');
        $glueResponseTransfer->setStatus((string)Response::HTTP_INTERNAL_SERVER_ERROR);
        $glueResponseTransfer->setContent('Missing encoder for ' . $format);

        return $glueResponseTransfer;
    }
}
