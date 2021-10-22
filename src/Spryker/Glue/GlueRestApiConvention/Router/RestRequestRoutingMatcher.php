<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\Router;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueRestApiConvention\Exception\Router\MissingRequestMethodException;
use Spryker\Glue\GlueRestApiConvention\Resource\ResourceBuilder;
use Spryker\Glue\GlueRestApiConvention\Resource\ResourceBuilderInterface;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Resource\ResourceInterface;

class RestRequestRoutingMatcher implements RestRequestRoutingMatcherInterface
{
    protected RequestResourcePluginFilterInterface $resourcePluginFilter;

    protected ResourceBuilder $resourceBuilder;

    /**
     * @param \Spryker\Glue\GlueRestApiConvention\Router\RequestResourcePluginFilterInterface $resourcePluginFilter
     * @param \Spryker\Glue\GlueRestApiConvention\Resource\ResourceBuilderInterface $resourceBuilder
     */
    public function __construct(
        RequestResourcePluginFilterInterface $resourcePluginFilter,
        ResourceBuilderInterface $resourceBuilder
    ) {
        $this->resourcePluginFilter = $resourcePluginFilter;
        $this->resourceBuilder = $resourceBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param array<\Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResourceRoutePluginInterface> $resourceRoutePlugins
     *
     * @return \Spryker\Glue\GlueRestApiConventionExtension\Dependency\Resource\ResourceInterface
     */
    public function matchRequest(GlueRequestTransfer $glueRequestTransfer, array $resourceRoutePlugins): ResourceInterface
    {
        $resourceRoutePlugin = $this->resourcePluginFilter->filterPlugins($glueRequestTransfer, $resourceRoutePlugins);

        if (!$resourceRoutePlugin) {
            return $this->resourceBuilder->buildMissingResource();
        }

        $resourceMethodCollection = $resourceRoutePlugin->configure(new ResourceRouteCollection());
        $requestMethod = $this->getRequestMethod($glueRequestTransfer);

        if (
            $requestMethod === ResourceRouteCollection::METHOD_OPTIONS
            && !$resourceMethodCollection->has(ResourceRouteCollection::METHOD_OPTIONS)
        ) {
            return $this->resourceBuilder->buildPreFlightResource($resourceMethodCollection);
        }

        if (!$resourceMethodCollection->has($requestMethod)) {
            return $this->resourceBuilder->buildMissingResource();
        }

        return $this->resourceBuilder->buildResource($resourceRoutePlugin, $resourceMethodCollection, $requestMethod);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @throws \Spryker\Glue\GlueRestApiConvention\Exception\Router\MissingRequestMethodException
     *
     * @return string
     */
    protected function getRequestMethod(GlueRequestTransfer $glueRequestTransfer): string
    {
        if (empty($glueRequestTransfer->getMethod())) {
            throw new MissingRequestMethodException('Empty request method can not be mapped to a controller action');
        }

        $method = strtoupper($glueRequestTransfer->getMethod());

        if (
            $method === ResourceRouteCollection::METHOD_GET
            && $glueRequestTransfer->getResource()
            && $glueRequestTransfer->getResource()->getId() === null
        ) {
            return ResourceRouteCollection::METHOD_GET_COLLECTION;
        }

        return $method;
    }
}
