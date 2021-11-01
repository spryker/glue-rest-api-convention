<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\Resource;

use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface as GlueResourceRouteCollectionInterface;

interface ResourceRouteCollectionInterface extends GlueResourceRouteCollectionInterface
{
    /**
     * Specification:
     * - Map pre-flight method (OPTIONS).
     * - Should be mapped to a controller action that returns the according CORS headers.
     * - @see \Spryker\Glue\GlueRestApiConvention\Router\RestRequestRoutingMatcher will provide a default if not mapped.
     *
     * @api
     *
     * @param string $actionName
     * @param array<string, mixed> $context
     *
     * @return self
     */
    public function addOptions(string $actionName, array $context = []): self;

    /**
     * Specification:
     * - Map GET method to a controller action.
     * - Is only called to retrieve a collection of resources, for a single resources use addGet().
     * - Configures if the controller action can be called with valid authentication token only.
     * - Additional context can be passed that will be passed into the controller action.
     *
     * @api
     *
     * @param string $actionName
     * @param bool $isProtected
     * @param array<string, mixed> $context
     *
     * @return self
     */
    public function addGetCollection(string $actionName, bool $isProtected = true, array $context = []): self;
}
