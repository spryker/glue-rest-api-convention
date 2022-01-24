<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Spryker\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention\CorsRequestAfterRoutingValidatorPlugin;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\RestResourceInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueRestApiConvention
 * @group Plugin
 * @group GlueRestApiConvention
 * @group CorsRequestAfterRoutingValidatorPluginTest
 * Add your own group annotations below this line
 */
class CorsRequestAfterRoutingValidatorPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\GlueRestApiConvention\GlueRestApiConventionTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCorsRequestAfterRoutingValidatorPlugin(): void
    {
        //Arrange
        $restResourceInterfaceMock = $this->getMockBuilder(RestResourceInterface::class)->getMock();
        $glueRequestTransfer = $this->tester->createGlueRequestTransfer();

        //Act
        $corsRequestAfterRoutingValidatorPlugin = new CorsRequestAfterRoutingValidatorPlugin();
        $glueRequestValidationTransfer = $corsRequestAfterRoutingValidatorPlugin->validateRequest($glueRequestTransfer, $restResourceInterfaceMock);

        //Assert
        $this->assertInstanceOf(GlueRequestValidationTransfer::class, $glueRequestValidationTransfer);
        $this->assertTrue($glueRequestValidationTransfer->getIsValid());
    }
}
