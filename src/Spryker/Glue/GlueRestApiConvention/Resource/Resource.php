<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\Resource;

use Spryker\Glue\GlueApplication\Resource\Resource as GlueApplicationResource;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;

class Resource extends GlueApplicationResource implements ResourceInterface
{
    protected ?ResourceRouteCollectionInterface $resourceRouteCollection;

    /**
     * @param callable $executableResource
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $resourceRoutePlugin
     * @param \Spryker\Glue\GlueRestApiConvention\Resource\ResourceRouteCollectionInterface $resourceRouteCollection
     */
    public function __construct(
        callable $executableResource,
        ResourceRoutePluginInterface $resourceRoutePlugin,
        ResourceRouteCollectionInterface $resourceRouteCollection
    ) {
        parent::__construct($executableResource, $resourceRoutePlugin);
        $this->resourceRouteCollection = $resourceRouteCollection;
    }

    /**
     * @return \Spryker\Glue\GlueRestApiConvention\Resource\ResourceRouteCollectionInterface|null
     */
    public function getMatchingResourceCollection(): ?ResourceRouteCollectionInterface
    {
        return $this->resourceRouteCollection;
    }

    /**
     * @return callable():\Generated\Shared\Transfer\GlueResponseTransfer|null
     */
    public function getResource(): callable
    {
        return parent::getResource();
    }
}
