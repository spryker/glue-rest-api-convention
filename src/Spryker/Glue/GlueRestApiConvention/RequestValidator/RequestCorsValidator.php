<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\RequestValidator;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionConfig;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\RestResourceInterface;
use Symfony\Component\HttpFoundation\Request;

class RequestCorsValidator implements RequestCorsValidatorInterface
{
    /**
     * @var string
     */
    protected const HEADER_ACCESS_CONTROL_ALLOW_HEADERS = 'access-control-allow-headers';

    /**
     * @var string
     */
    protected const HEADER_ACCESS_CONTROL_REQUEST_HEADERS = 'access-control-request-headers';

    /**
     * @var string
     */
    protected const HEADER_ACCESS_CONTROL_REQUEST_METHOD = 'access-control-request-method';

    /**
     * @var string
     */
    protected const HEADER_ACCESS_CONTROL_ALLOW_METHODS = 'access-control-allow-methods';

    /**
     * @var string
     */
    protected const METHOD_GET_COLLECTION = 'get_collection';

    protected GlueRestApiConventionConfig $config;

    /**
     * @param \Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionConfig $config
     */
    public function __construct(GlueRestApiConventionConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\RestResourceInterface $restResourcePlugin
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    public function validate(
        GlueRequestTransfer $glueRequestTransfer,
        RestResourceInterface $restResourcePlugin
    ): GlueRequestValidationTransfer {
        $headers = $glueRequestTransfer->getMeta();

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
        if (empty($headers[static::HEADER_ACCESS_CONTROL_REQUEST_HEADERS])) {
            return (new GlueRequestValidationTransfer())->setIsValid(true);
        }

        $requestedHeaders = explode(', ', (string)$headers[static::HEADER_ACCESS_CONTROL_REQUEST_HEADERS]);
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
        $headers[static::HEADER_ACCESS_CONTROL_REQUEST_METHOD] ??= null;
        $method = strtoupper($headers[static::HEADER_ACCESS_CONTROL_REQUEST_METHOD]);

        if (!$method || $method === Request::METHOD_OPTIONS) {
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
        $availableMethods = array_keys(array_filter($restResourcePlugin->getDeclaredMethods()->toArray()));

        $index = array_search(static::METHOD_GET_COLLECTION, $availableMethods);

        if ($index !== false) {
            unset($availableMethods[$index]);
            $availableMethods[] = Request::METHOD_GET;
        }

        return array_map('strtoupper', array_unique($availableMethods));
    }
}
