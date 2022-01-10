<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\RequestValidator;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Symfony\Component\HttpFoundation\Response;

class AcceptedFormatValidator implements AcceptedFormatValidatorInterface
{
    /**
     * @var \Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResponseEncoderPluginInterface[]
     */
    protected array $responseEncoderPlugins;

    /**
     * @param array<\Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResponseEncoderPluginInterface> $responseEncoderPlugins
     */
    public function __construct(array $responseEncoderPlugins)
    {
        $this->responseEncoderPlugins = $responseEncoderPlugins;
    }

    /**
     * {@inheritDoc}
     * - Validates if the requested doc format can be served by this convention.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    public function validate(GlueRequestTransfer $glueRequestTransfer): GlueRequestValidationTransfer
    {
        if (!$glueRequestTransfer->getAcceptedFormats()) {
            return (new GlueRequestValidationTransfer())->setIsValid(true);
        }

        foreach ($this->responseEncoderPlugins as $responseEncoderPlugin) {
            if (array_intersect($glueRequestTransfer->getAcceptedFormats(), $responseEncoderPlugin->getAcceptedFormats())) {
                return (new GlueRequestValidationTransfer())->setIsValid(true);
            }
        }

        return (new GlueRequestValidationTransfer())
            ->setIsValid(false)
            ->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->setValidationError('Unsupported "Accept" format used. ');
    }
}
