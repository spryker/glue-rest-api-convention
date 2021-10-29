<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueRestApiConvention\Router\Stub;

use Generated\Shared\Transfer\GlueVersionTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResourceRouteWithParentsPluginInterface;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\VersionedResourceRoutePluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

class RouterPluginStub extends AbstractPlugin implements VersionedResourceRoutePluginInterface, ResourceRouteWithParentsPluginInterface
{
    /**
     * @var string
     */
    protected string $resourceType;

    /**
     * @var array<string>
     */
    protected array $parentResourceTypes = [];

    /**
     * @var \Generated\Shared\Transfer\GlueVersionTransfer
     */
    protected GlueVersionTransfer $versionTransfer;

    /**
     * @param string $resourceType
     * @param array $parentResourceTypes
     * @param \Generated\Shared\Transfer\GlueVersionTransfer $versionTransfer
     */
    public function __construct(
        string $resourceType,
        array $parentResourceTypes,
        GlueVersionTransfer $versionTransfer
    ) {
        $this->resourceType = $resourceType;
        $this->parentResourceTypes = $parentResourceTypes;
        $this->versionTransfer = $versionTransfer;
    }

    /**
     * @param \Spryker\Glue\GlueRestApiConvention\Resource\ResourceRouteCollectionInterface $resourceRouteCollection
     *
     * @return \Spryker\Glue\GlueRestApiConvention\Resource\ResourceRouteCollectionInterface
     */
    public function configure(ResourceRouteCollectionInterface $resourceRouteCollection): ResourceRouteCollectionInterface
    {
        return $resourceRouteCollection;
    }

    /**
     * @return string
     */
    public function getResourceType(): string
    {
        return $this->resourceType;
    }

    /**
     * @return string
     */
    public function getController(): string
    {
        return 'stub-controller';
    }

    /**
     * @return array<string>
     */
    public function getParentResourceTypes(): array
    {
        return $this->parentResourceTypes;
    }

    /**
     * @return \Generated\Shared\Transfer\GlueVersionTransfer
     */
    public function getMatchingVersion(): GlueVersionTransfer
    {
        return $this->versionTransfer;
    }

    /**
     * @return string
     */
    public function getResourceAttributesClassName(): string
    {
        return 'foo';
    }
}
