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
use Spryker\Glue\Kernel\BundleControllerAction;
use Spryker\Shared\Kernel\ClassResolver\Controller\AbstractControllerResolver;
use Spryker\Shared\Kernel\ClassResolver\Controller\ControllerNotFoundException;

class ResourceBuilder implements ResourceBuilderInterface
{
    protected GlueRestApiConventionConfig $config;

    protected AbstractControllerResolver $controllerResolver;

    /**
     * @param \Spryker\Shared\Kernel\ClassResolver\Controller\AbstractControllerResolver $controllerResolver
     * @param \Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionConfig $config
     */
    public function __construct(
        AbstractControllerResolver $controllerResolver,
        GlueRestApiConventionConfig $config
    ) {
        $this->config = $config;
        $this->controllerResolver = $controllerResolver;
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
        $method = $resourceRouteCollection->get($requestMethod)[ResourceRouteCollection::CONTROLLER_ACTION];

        try {
            $controller = $this->getController($resourceRoutePlugin, $method);
        } catch (ControllerNotFoundException $exception) {
            return new MissingResource('500', $exception->getMessage());
        }

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
            get_class($controller)
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

    /**
     * @param \Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResourceRoutePluginInterface $resourceRoutePlugin
     * @param string $action
     *
     * @return object
     */
    protected function getController(ResourceRoutePluginInterface $resourceRoutePlugin, string $action)
    {
        // Reflection would be faster but will trigger an autoloading error if the controller does not exists in core
        $controllerNameParts = explode('\\', $resourceRoutePlugin->getController());
        $controllerName = array_pop($controllerNameParts);
        $controllerName = preg_replace('/Controller$/', '', $controllerName);

        $bundleControllerAction = new BundleControllerAction(
            $resourceRoutePlugin->getModuleName(),
            $controllerName,
            $action
        );

        return $this->controllerResolver->resolve($bundleControllerAction);
    }
}
