<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention;

use Spryker\Glue\GlueRestApiConvention\Controller\ControllerResolver;
use Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestPaginationParameterBuilder;
use Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestPaginationParameterBuilderInterface;
use Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestQueryParameterBuilder;
use Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestQueryParameterBuilderInterface;
use Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestRestResourceBuilder;
use Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestRestResourceBuilderInterface;
use Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestSortParameterBuilder;
use Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestSortParameterBuilderInterface;
use Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestVersionBuilder;
use Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestVersionBuilderInterface;
use Spryker\Glue\GlueRestApiConvention\RequestValidator\RequestCorsValidator;
use Spryker\Glue\GlueRestApiConvention\RequestValidator\RequestCorsValidatorInterface;
use Spryker\Glue\GlueRestApiConvention\RequestValidator\RequestPaginationValidator;
use Spryker\Glue\GlueRestApiConvention\RequestValidator\RequestPaginationValidatorInterface;
use Spryker\Glue\GlueRestApiConvention\Resource\ResourceBuilder;
use Spryker\Glue\GlueRestApiConvention\Resource\ResourceBuilderInterface;
use Spryker\Glue\GlueRestApiConvention\Router\RequestResourcePluginFilter;
use Spryker\Glue\GlueRestApiConvention\Router\RequestResourcePluginFilterInterface;
use Spryker\Glue\GlueRestApiConvention\Router\RestRequestRoutingMatcher;
use Spryker\Glue\GlueRestApiConvention\Router\RestRequestRoutingMatcherInterface;
use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Shared\Kernel\ClassResolver\Controller\AbstractControllerResolver;

/**
 * @method \Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionConfig getConfig()
 */
class GlueJsonApiConventionFactory extends AbstractFactory
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
     * @return \Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestVersionBuilderInterface
     */
    public function createRequestVersionBuilder(): RequestVersionBuilderInterface
    {
        return new RequestVersionBuilder();
    }

    /**
     * @return \Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestRestResourceBuilderInterface
     */
    public function createRequestRestResourceBuilder(): RequestRestResourceBuilderInterface
    {
        return new RequestRestResourceBuilder();
    }

    /**
     * @return \Spryker\Glue\GlueRestApiConvention\RequestValidator\RequestPaginationValidatorInterface
     */
    public function createRequestPaginationValidator(): RequestPaginationValidatorInterface
    {
        return new RequestPaginationValidator();
    }

    /**
     * @return \Spryker\Glue\GlueRestApiConvention\RequestValidator\RequestCorsValidatorInterface
     */
    public function createRequestCorsValidator(): RequestCorsValidatorInterface
    {
        return new RequestCorsValidator($this->getConfig());
    }

    /**
     * @return \Spryker\Glue\GlueRestApiConvention\Router\RestRequestRoutingMatcherInterface
     */
    public function createRequestRoutingMatcher(): RestRequestRoutingMatcherInterface
    {
        return new RestRequestRoutingMatcher(
            $this->createRequestResourcePluginFilter(),
            $this->createResourceBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\GlueRestApiConvention\Router\RequestResourcePluginFilterInterface
     */
    protected function createRequestResourcePluginFilter(): RequestResourcePluginFilterInterface
    {
        return new RequestResourcePluginFilter();
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
}
