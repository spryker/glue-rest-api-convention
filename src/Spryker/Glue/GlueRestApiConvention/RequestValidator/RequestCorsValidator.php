<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\RequestValidator;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Spryker\Glue\GlueRestApiConvention\Cors\CorsConstants;
use Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionConfig;
use Spryker\Glue\GlueRestApiConvention\Router\ResourceRouteCollection;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\RestResourceInterface;

class RequestCorsValidator implements RequestCorsValidatorInterface
{
    protected GlueRestApiConventionConfig $config;

    /**
     * @param \Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionConfig $config
     */
    public function __construct(GlueRestApiConventionConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequest
     * @param \Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\RestResourceInterface $restResourcePlugin
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    public function validate(
        GlueRequestTransfer $glueRequest,
        RestResourceInterface $restResourcePlugin
    ): GlueRequestValidationTransfer {
        $headers = $glueRequest->getMeta();

        $corsMethodValidation = $this->validateCorsMethod($headers, $restResourcePlugin);

        if ($corsMethodValidation === null) {
            return (new GlueRequestValidationTransfer())->setIsValid(true);
        }

        if (!$corsMethodValidation->getIsValid()) {
            return $corsMethodValidation;
        }

        return $this->validateHeaders($headers);
    }

    /**
     * @param array $headers
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    protected function validateHeaders(array $headers): GlueRequestValidationTransfer
    {
        if (!isset($headers[CorsConstants::HEADER_ACCESS_CONTROL_REQUEST_HEADERS]) || empty($headers[CorsConstants::HEADER_ACCESS_CONTROL_REQUEST_HEADERS])) {
            return (new GlueRequestValidationTransfer())->setIsValid(true);
        }

        $requestedHeaders = explode(', ', (string)$headers[CorsConstants::HEADER_ACCESS_CONTROL_REQUEST_HEADERS]);
        $requestedHeaders = array_map('strtolower', $requestedHeaders);
        $allowedHeaders = array_map('strtolower', $this->config->getCorsAllowedHeaders());

        foreach ($requestedHeaders as $requestedHeader) {
            if (in_array($requestedHeader, $allowedHeaders, false)) {
                continue;
            }

            return (new GlueRequestValidationTransfer())
                ->setIsValid(false)
                ->setValidationError('Not allowed.')
                ->setStatusCode('400');
        }

        return (new GlueRequestValidationTransfer())->setIsValid(true);
    }

    /**
     * @param array $headers
     * @param \Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\RestResourceInterface $restResourcePlugin
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer|null
     */
    protected function validateCorsMethod(array $headers, RestResourceInterface $restResourcePlugin): ?GlueRequestValidationTransfer
    {
        $headers[CorsConstants::HEADER_ACCESS_CONTROL_REQUEST_METHOD] ??= null;
        $method = strtoupper($headers[CorsConstants::HEADER_ACCESS_CONTROL_REQUEST_METHOD]);

        if (!$method || $method === ResourceRouteCollection::METHOD_OPTIONS) {
            return null;
        }

        $validationResult = (new GlueRequestValidationTransfer());

        $availableMethods = $this->getAvailableMethods($restResourcePlugin);

        if (!in_array($method, $availableMethods)) {
            return $validationResult
                ->setIsValid(false)
                ->setStatusCode('400')
                ->setValidationError('Not allowed.');
        }

        return $validationResult->setIsValid(true);
    }

    /**
     * @param \Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\RestResourceInterface $restResourcePlugin
     *
     * @return array
     */
    protected function getAvailableMethods(RestResourceInterface $restResourcePlugin): array
    {
        $availableMethods = $restResourcePlugin->configure(new ResourceRouteCollection())
            ->getAvailableMethods();

        $index = array_search(ResourceRouteCollection::METHOD_GET_COLLECTION, $availableMethods);

        if ($index !== false) {
            unset($availableMethods[$index]);
            $availableMethods[] = ResourceRouteCollection::METHOD_GET;
        }

        return array_map('strtoupper', array_unique($availableMethods));
    }
}
