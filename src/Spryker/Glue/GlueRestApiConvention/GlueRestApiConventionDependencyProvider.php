<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionConfig getConfig()
 */
class GlueRestApiConventionDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const SERVICE_ENCODING = 'UTIL_JSON_ENCODER';

    /**
     * @var string
     */
    public const PLUGIN_RESPONSE_ENCODER = 'PLUGIN_RESPONSE_ENCODER';

    /**
     * @var string
     */
    public const PLUGIN_RESPONSE_EXPANDER = 'PLUGIN_RESPONSE_EXPANDER';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = parent::provideDependencies($container);
        $container = $this->addJsonEncoder($container);
        $container = $this->addResponseEncoderPlugins($container);
        $container = $this->addResponseExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addJsonEncoder(Container $container): Container
    {
        $container->set(static::SERVICE_ENCODING, function (Container $container) {
            return $container->getLocator()->utilEncoding()->service();
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addResponseEncoderPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_RESPONSE_ENCODER, function () {
            return $this->getResponseEncoderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResponseEncoderPluginInterface>
     */
    protected function getResponseEncoderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addResponseExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_RESPONSE_EXPANDER, function () {
            return $this->getResponseExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResponseExpanderPluginInterface>
     */
    protected function getResponseExpanderPlugins(): array
    {
        return [];
    }
}
