<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention;

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
use Spryker\Glue\Kernel\AbstractFactory;

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
}
