<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\Resource;

use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionConfig;
use Spryker\Glue\GlueRestApiConvention\Router\ResourceRouteCollection;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Resource\ResourceInterface;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Resource\ResourceRouteCollectionInterface;

//@todo unit test
class ResourceBuilder implements ResourceBuilderInterface
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
     * @param \Spryker\Glue\GlueRestApiConventionExtension\Dependency\Resource\ResourceRouteCollectionInterface $resourceRouteCollection
     *
     * @return \Spryker\Glue\GlueRestApiConventionExtension\Dependency\Resource\ResourceInterface
     */
    public function buildPreFlightResource(ResourceRouteCollectionInterface $resourceRouteCollection): ResourceInterface
    {
        return $this->createResource(
            function () use ($resourceRouteCollection): GlueResponseTransfer {
                return (new GlueResponseTransfer())
                    ->addMeta('access-control-allow-methods', implode(', ', $resourceRouteCollection->getAvailableMethods()))
                    ->addMeta('access-control-allow-headers', $this->config->getCorsAllowedHeaders());
            },
            $resourceRouteCollection
        );
    }

    /**
     * @return \Spryker\Glue\GlueRestApiConvention\Resource\MissingResource
     */
    public function buildMissingResource(): MissingResource
    {
        return new MissingResource(
            '404',
            'No route found'
        );
    }

    /**
     * @param \Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResourceRoutePluginInterface $resourceRoutePlugin
     * @param \Spryker\Glue\GlueRestApiConventionExtension\Dependency\Resource\ResourceRouteCollectionInterface $resourceRouteCollection
     * @param string $requestMethod
     *
     * @return \Spryker\Glue\GlueRestApiConventionExtension\Dependency\Resource\ResourceInterface
     */
    public function buildResource(
        ResourceRoutePluginInterface $resourceRoutePlugin,
        ResourceRouteCollectionInterface $resourceRouteCollection,
        string $requestMethod
    ): ResourceInterface {
        //@todo use controller resolver to be able to overwrite controller in project
        $controller = $resourceRoutePlugin->getController();

        if (!class_exists($controller)) {
            return new MissingResource('500', sprintf('Controller %s not found', $controller));
        }

        $method = $resourceRouteCollection->get($requestMethod)[ResourceRouteCollection::CONTROLLER_ACTION];

        if (method_exists($controller, $method)) {
            return $this->createResource([$controller, $method], $resourceRouteCollection);
        }

        $methodAction = $method . 'Action';

        if (method_exists($controller, $methodAction)) {
            return $this->createResource([$controller, $methodAction], $resourceRouteCollection);
        }

        return new MissingResource('500', sprintf(
            'Neither %s() nor %s() found in %s',
            $method,
            $methodAction,
            $controller
        ));
    }

    /**
     * @param callable $action
     * @param \Spryker\Glue\GlueRestApiConventionExtension\Dependency\Resource\ResourceRouteCollectionInterface $resourceRouteCollection
     *
     * @return \Spryker\Glue\GlueRestApiConventionExtension\Dependency\Resource\ResourceInterface
     */
    protected function createResource(callable $action, ResourceRouteCollectionInterface $resourceRouteCollection): ResourceInterface
    {
        return new Resource($action, $resourceRouteCollection);
    }
}
