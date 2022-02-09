<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\StoresRestAttributesTransfer;
use Spryker\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention\AttributeResponseExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueRestApiConvention
 * @group Plugin
 * @group GlueRestApiConvention
 * @group AttributeResponseExpanderPluginTest
 * Add your own group annotations below this line
 */
class AttributeResponseExpanderPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\GlueRestApiConvention\GlueRestApiConventionTester
     */
    protected $tester;

    /**
     * @var string
     */
    protected const LOCALES_KEY = 'locales';

    /**
     * @var string
     */
    protected const TIME_ZONE_KEY = 'timeZone';

    /**
     * @var string
     */
    protected const TIME_ZONE_VALUE = 'Europe/Berlin';

    /**
     * @var string
     */
    protected const DEFAULT_CURRENCY_KEY = 'defaultCurrency';

    /**
     * @var string
     */
    protected const DEFAULT_CURRENCY_VALUE = 'EUR';

    /**
     * @return void
     */
    public function testAttributeResponseExpanderPlugin(): void
    {
        //Arrange
        $glueRequestTransfer = $this->tester->createGlueRequestTransfer();
        $glueResponseTransfer = (new GlueResponseTransfer())->setResources(new ArrayObject([(new GlueResourceTransfer())->setAttributes($this->createFakeStoresRestAttributeTransfer())]));

        //Act
        $attributeResponseExpanderPlugin = new AttributeResponseExpanderPlugin();
        $result = $attributeResponseExpanderPlugin->expand($glueResponseTransfer, $glueRequestTransfer, []);

        //Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey(static::TIME_ZONE_KEY, $result);
        $this->assertSame(static::TIME_ZONE_VALUE, $result[static::TIME_ZONE_KEY]);
        $this->assertArrayHasKey(static::DEFAULT_CURRENCY_KEY, $result);
        $this->assertSame(static::DEFAULT_CURRENCY_VALUE, $result[static::DEFAULT_CURRENCY_KEY]);
        $this->assertArrayHasKey(static::LOCALES_KEY, $result);
        $this->assertEmpty($result[static::LOCALES_KEY]);
    }

    /**
     * @return \Generated\Shared\Transfer\StoresRestAttributesTransfer
     */
    protected function createFakeStoresRestAttributeTransfer(): StoresRestAttributesTransfer
    {
        return (new StoresRestAttributesTransfer())
            ->setTimezone(static::TIME_ZONE_VALUE)
            ->setDefaultCurrency(static::DEFAULT_CURRENCY_VALUE);
    }
}
