<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention;

use Spryker\Glue\GlueRestApiConvention\Controller\ControllerResolver;
use Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestFormatBuilder;
use Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestFormatBuilderInterface;
use Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestPaginationParameterBuilder;
use Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestPaginationParameterBuilderInterface;
use Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestQueryParameterBuilder;
use Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestQueryParameterBuilderInterface;
use Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestSortParameterBuilder;
use Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestSortParameterBuilderInterface;
use Spryker\Glue\GlueRestApiConvention\RequestValidator\RequestCorsValidator;
use Spryker\Glue\GlueRestApiConvention\RequestValidator\RequestCorsValidatorInterface;
use Spryker\Glue\GlueRestApiConvention\Resource\ResourceBuilder;
use Spryker\Glue\GlueRestApiConvention\Resource\ResourceBuilderInterface;
use Spryker\Glue\GlueRestApiConvention\ResponseBuilder\Expander\AttributeExpander;
use Spryker\Glue\GlueRestApiConvention\ResponseBuilder\Expander\AttributeExpanderInterface;
use Spryker\Glue\GlueRestApiConvention\ResponseBuilder\ResponseContentBuilder;
use Spryker\Glue\GlueRestApiConvention\ResponseBuilder\ResponseContentBuilderInterface;
use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use Spryker\Shared\Kernel\ClassResolver\Controller\AbstractControllerResolver;

/**
 * @method \Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionConfig getConfig()
 */
class GlueRestApiConventionFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestQueryParameterBuilderInterface
     */
    public function createRequestQueryParameterBuilder(): RequestQueryParameterBuilderInterface
    {
        return new RequestQueryParameterBuilder();
    }

    /**
     * @return \Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestPaginationParameterBuilderInterface
     */
    public function createRequestPaginationParameterBuilder(): RequestPaginationParameterBuilderInterface
    {
        return new RequestPaginationParameterBuilder();
    }

    /**
     * @return \Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestSortParameterBuilderInterface
     */
    public function createRequestSortParameterBuilder(): RequestSortParameterBuilderInterface
    {
        return new RequestSortParameterBuilder();
    }

    /**
     * @return \Spryker\Glue\GlueRestApiConvention\RequestValidator\RequestCorsValidatorInterface
     */
    public function createRequestCorsValidator(): RequestCorsValidatorInterface
    {
        return new RequestCorsValidator($this->getConfig());
    }

    /**
     * @return \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    public function getEncodingService(): UtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(GlueRestApiConventionDependencyProvider::SERVICE_ENCODING);
    }

    /**
     * @return \Spryker\Glue\GlueRestApiConvention\ResponseBuilder\Expander\AttributeExpanderInterface
     */
    public function createAttributesExpander(): AttributeExpanderInterface
    {
        return new AttributeExpander();
    }

    /**
     * @return \Spryker\Glue\GlueRestApiConvention\ResponseBuilder\ResponseContentBuilderInterface
     */
    public function createResponseContentBuilder(): ResponseContentBuilderInterface
    {
        return new ResponseContentBuilder(
            $this->getResponseEncoderPlugins(),
            $this->getResponseExpanderPlugins(),
        );
    }

    /**
     * @return \Spryker\Glue\GlueRestApiConvention\Resource\ResourceBuilderInterface
     */
    protected function createResourceBuilder(): ResourceBuilderInterface
    {
        return new ResourceBuilder($this->createControllerResolver(), $this->getConfig());
    }

    /**
     * @return \Spryker\Shared\Kernel\ClassResolver\Controller\AbstractControllerResolver
     */
    protected function createControllerResolver(): AbstractControllerResolver
    {
        return new ControllerResolver();
    }

    /**
     * @return array<\Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResponseEncoderPluginInterface>
     */
    protected function getResponseEncoderPlugins(): array
    {
        return $this->getProvidedDependency(GlueRestApiConventionDependencyProvider::PLUGIN_RESPONSE_ENCODER);
    }

    /**
     * @return array<\Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResponseExpanderPluginInterface>
     */
    protected function getResponseExpanderPlugins(): array
    {
        return $this->getProvidedDependency(GlueRestApiConventionDependencyProvider::PLUGIN_RESPONSE_EXPANDER);
    }

    /**
     * @return array<\Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\RequestBuilderPluginInterface>
     */
    public function getRequestBuilderPlugins(): array
    {
        return $this->getProvidedDependency(GlueRestApiConventionDependencyProvider::PLUGINS_REQUEST_BUILDER);
    }

    /**
     * @return array<\Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\RequestValidatorPluginInterface>
     */
    public function getRequestValidatorPlugins(): array
    {
        return $this->getProvidedDependency(GlueRestApiConventionDependencyProvider::PLUGINS_REQUEST_VALIDATOR);
    }

    /**
     * @return array<\Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\RequestAfterRoutingValidatorPluginInterface>
     */
    public function getRequestAfterRoutingValidatorPlugins(): array
    {
        return $this->getProvidedDependency(GlueRestApiConventionDependencyProvider::PLUGINS_REQUEST_AFTER_ROUTING_VALIDATOR);
    }

    /**
     * @return array<\Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResponseFormatterPluginInterface>
     */
    public function getResponseFormatterPlugins(): array
    {
        return $this->getProvidedDependency(GlueRestApiConventionDependencyProvider::PLUGINS_RESPONSE_FORMATTER);
    }
    /**
     * @return \Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestQueryParameterBuilderInterface
     */
    public function createRequestFormatBuilder(): RequestFormatBuilderInterface
    {
        return new RequestFormatBuilder();
    }
}
