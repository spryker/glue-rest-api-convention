<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention;

use Codeception\Test\Unit;
use Spryker\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention\JsonResponseEncoderPlugin;
use stdClass;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueRestApiConvention
 * @group Plugin
 * @group GlueRestApiConvention
 * @group JsonResponseEncoderPluginTest
 * Add your own group annotations below this line
 */
class JsonResponseEncoderPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testAcceptedFormatJson(): void
    {
        //Act
        $plugin = new JsonResponseEncoderPlugin();
        $result = $plugin->getAcceptedFormats();

        //Assert
        $this->assertSame(['application/json'], $result);
    }

    /**
     * @dataProvider acceptedTypesDataProvider
     *
     * @param mixed $content
     *
     * @return void
     */
    public function testAcceptTypes($content): void
    {
        //Act
        $plugin = new JsonResponseEncoderPlugin();
        $result = $plugin->accepts($content);

        //Assert
        $this->assertTrue($result);
    }

    /**
     * @dataProvider acceptedTypesDataProvider
     *
     * @param mixed $content
     *
     * @return void
     */
    public function testUsesEncodingService($content): void
    {
        //Act
        $jsonResponseEncoderPlugin = new JsonResponseEncoderPlugin();
        $jsonResponseEncoderPlugin->encode($content);
    }

    /**
     * @return array
     */
    public function acceptedTypesDataProvider(): array
    {
        return [
            ['string'],
            [100],
            [1.2],
            [['array_key' => 'array_value']],
            [null],
            [(new stdClass())],
        ];
    }
}
