<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\Router;

use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Resource\ResourceRouteCollectionInterface;

//@todo unittests
class ResourceRouteCollection implements ResourceRouteCollectionInterface
{
    /**
     * @var string
     */
    public const CONTROLLER_ACTION = 'action';

    /**
     * @var string
     */
    public const METHOD_CONTEXT = 'context';

    /**
     * @var string
     */
    public const IS_PROTECTED = 'is_protected';
    /**
     * @var string
     */
    public const METHOD_GET = 'GET';
    /**
     * @var string
     */
    public const METHOD_PATCH = 'PATCH';
    /**
     * @var string
     */
    public const METHOD_POST = 'POST';
    /**
     * @var string
     */
    public const METHOD_DELETE = 'DELETE';
    /**
     * @var string
     */
    public const METHOD_GET_COLLECTION = 'GET_COLLECTION';

    /**
     * @var string
     */
    public const METHOD_OPTIONS = 'OPTIONS';

    /**
     * @var array
     */
    protected $actions = [];

    /**
     * @inheritDoc
     */
    public function has(string $method): bool
    {
        return isset($this->actions[$method]);
    }

    /**
     * @inheritDoc
     */
    public function get(string $method): array
    {
        return $this->actions[$method];
    }

    /**
     * @inheritDoc
     */
    public function addOptions(string $actionName, array $context = []): ResourceRouteCollectionInterface
    {
        $this->addAction(static::METHOD_OPTIONS, $actionName, false, $context);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addGet(string $actionName, bool $isProtected = true, array $context = []): self
    {
        $this->addAction(static::METHOD_GET, $actionName, $isProtected, $context);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addGetCollection(string $actionName, bool $isProtected = true, array $context = []): self
    {
        $this->addAction(static::METHOD_GET_COLLECTION, $actionName, $isProtected, $context);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addPost(string $actionName, bool $isProtected = true, array $context = []): self
    {
        $this->addAction(static::METHOD_POST, $actionName, $isProtected, $context);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addDelete(string $actionName, bool $isProtected = true, array $context = []): self
    {
        $this->addAction(static::METHOD_DELETE, $actionName, $isProtected, $context);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addPatch(string $actionName, bool $isProtected = true, array $context = []): self
    {
        $this->addAction(static::METHOD_PATCH, $actionName, $isProtected, $context);

        return $this;
    }

    /**
     * @return array
     */
    public function getAvailableMethods(): array
    {
        return array_keys($this->actions);
    }

    /**
     * @param string $method
     * @param string $action
     * @param bool $isProtected
     * @param array<string, mixed> $context
     *
     * @return void
     */
    protected function addAction(string $method, string $action, bool $isProtected, array $context): void
    {
        $this->actions[$method] = [
            static::CONTROLLER_ACTION => $action,
            static::METHOD_CONTEXT => $context,
            static::IS_PROTECTED => $isProtected,
        ];
    }
}
