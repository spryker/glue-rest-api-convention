<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\Router;

use ArrayObject;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueVersionTransfer;
use Spryker\Glue\GlueRestApiConvention\Exception\Router\AmbiguousRouteMatchingException;
use Spryker\Glue\GlueRestApiConventionExtension\Plugin\ResourceRoutePluginInterface;
use Spryker\Glue\GlueRestApiConventionExtension\Plugin\ResourceRouteWithParentsPluginInterface;
use Spryker\Glue\GlueRestApiConventionExtension\Plugin\VersionedResourceRoutePluginInterface;

class RequestResourcePluginFilter
{
    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequest
     * @param array<\Spryker\Glue\GlueRestApiConventionExtension\Plugin\ResourceRoutePluginInterface> $routePlugins
     *
     * @throws \Spryker\Glue\GlueRestApiConvention\Exception\Router\AmbiguousRouteMatchingException
     *
     * @return \Spryker\Glue\GlueRestApiConventionExtension\Plugin\ResourceRoutePluginInterface
     */
    public function filterPlugins(GlueRequestTransfer $glueRequest, array $routePlugins): ?ResourceRoutePluginInterface
    {
        if (!$glueRequest->getResource()) {
            return null;
        }

        $filteredRoutePlugins = $this->filterByResource($routePlugins, $glueRequest);
        $filteredRoutePlugins = $this->filterByVersion($filteredRoutePlugins, $glueRequest->getVersion());
        $filteredRoutePlugins = $this->filterByParents($filteredRoutePlugins, $glueRequest->getParentResources());
        $filteredRoutePluginsCount = count($filteredRoutePlugins);

        if ($filteredRoutePluginsCount === 0) {
            return null;
        }

        if ($filteredRoutePluginsCount > 1) {
            throw new AmbiguousRouteMatchingException(sprintf(
                'More than one %s matched, did you missed to add %s or %s to one of the plugins?',
                ResourceRoutePluginInterface::class,
                VersionedResourceRoutePluginInterface::class,
                ResourceRouteWithParentsPluginInterface::class
            ));
        }

        return $filteredRoutePlugins[0];
    }

    /**
     * @param array<\Spryker\Glue\GlueRestApiConventionExtension\Plugin\ResourceRoutePluginInterface> $routePlugins
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequest
     *
     * @return array<ResourceRoutePluginInterface>
     */
    protected function filterByResource(array $routePlugins, GlueRequestTransfer $glueRequest): array
    {
        return array_filter(
            $routePlugins,
            function (ResourceRoutePluginInterface $resourceRoutePlugin) use ($glueRequest): bool {
                return $glueRequest->getResource()->getType() === $resourceRoutePlugin->getResourceType();
            }
        );
    }

    /**
     * @param array<\Spryker\Glue\GlueRestApiConventionExtension\Plugin\ResourceRoutePluginInterface> $routePlugins
     * @param \Generated\Shared\Transfer\GlueVersionTransfer|null $versionTransfer
     *
     * @return array<\Spryker\Glue\GlueRestApiConventionExtension\Plugin\ResourceRoutePluginInterface>
     */
    protected function filterByVersion(array $routePlugins, ?GlueVersionTransfer $versionTransfer): array
    {
        $versionedPlugins = array_filter($routePlugins, function (ResourceRoutePluginInterface $routePlugin) {
            return $routePlugin instanceof VersionedResourceRoutePluginInterface;
        });

        if (count($versionedPlugins) === 0) {
            return $routePlugins;
        }

        if (!$versionTransfer) {
            return [];
        }

        return array_filter(
            $versionedPlugins,
            function (VersionedResourceRoutePluginInterface $resourceRoutePlugin) use ($versionTransfer): bool {
                $matchingVersion = $resourceRoutePlugin->getMatchingVersion();

                if (
                    $matchingVersion->getMajor() === $versionTransfer->getMajor()
                    && $matchingVersion->getMinor() === null
                ) {
                    return true;
                }

                if (
                    $matchingVersion->getMajor() === $versionTransfer->getMajor()
                    && $matchingVersion->getMinor() === $versionTransfer->getMinor()
                ) {
                    return true;
                }

                return false;
            }
        );
    }

    /**
     * @param array<\Spryker\Glue\GlueRestApiConventionExtension\Plugin\ResourceRoutePluginInterface> $routePlugins
     * @param \ArrayObject<\Generated\Shared\Transfer\GlueResourceTransfer> $parentResources
     *
     * @return array<\Spryker\Glue\GlueRestApiConventionExtension\Plugin\ResourceRoutePluginInterface>
     */
    protected function filterByParents(array $routePlugins, ArrayObject $parentResources): array
    {
        $pluginsWithParents = array_filter($routePlugins, function (ResourceRoutePluginInterface $routePlugin) {
            return $routePlugin instanceof ResourceRouteWithParentsPluginInterface;
        });

        if (count($pluginsWithParents) === 0) {
            return $routePlugins;
        }

        if ($parentResources->count() === 0) {
            return [];
        }

        $parentTypes = array_keys($parentResources->getArrayCopy());

        return array_filter(
            $pluginsWithParents,
            function (ResourceRouteWithParentsPluginInterface $pluginWithParents) use ($parentTypes): bool {
                $diff = array_diff($pluginWithParents->getParentResourceTypes(), $parentTypes);

                return count($diff) === 0;
            }
        );
    }
}
