<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueRestApiConvention\Plugin;

use Codeception\Test\Unit;
use Spryker\Glue\GlueRestApiConvention\Dependency\Service\GlueRestApiConventionToUtilEncodingServiceInterface;
use Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionFactory;
use Spryker\Glue\GlueRestApiConvention\Plugin\JsonResponseEncoderPlugin;
use stdClass;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueRestApiConvention
 * @group Plugin
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
        $plugin = new JsonResponseEncoderPlugin();

        $result = $plugin->getAcceptedFormats();

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
        $plugin = new JsonResponseEncoderPlugin();

        $result = $plugin->accepts($content);

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
        $encoderMock = $this->createMock(GlueRestApiConventionToUtilEncodingServiceInterface::class);
        $encoderMock->expects($this->once())
            ->method('encodeJson')
            ->willReturnCallback(function ($data) {
                return json_encode($data);
            });
        $factoryMock = $this->createMock(GlueRestApiConventionFactory::class);
        $factoryMock->expects($this->once())
            ->method('getUtilEncodingService')
            ->willReturn($encoderMock);

        $plugin = new JsonResponseEncoderPlugin();
        $plugin->setFactory($factoryMock);

        $plugin->encode($content);
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
