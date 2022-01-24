<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention;

use Codeception\Test\Unit;
use Spryker\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention\AcceptFormatRequestValidatorPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueRestApiConvention
 * @group Plugin
 * @group GlueRestApiConvention
 * @group AcceptFormatRequestValidatorPluginTest
 * Add your own group annotations below this line
 */
class AcceptFormatRequestValidatorPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\GlueRestApiConvention\GlueRestApiConventionTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAcceptFormatRequestValidatorPlugin(): void
    {
        //Arrange
        $glueRequestTransfer = $this->tester->createGlueRequestTransfer();

        //Act
        $acceptFormatRequestValidatorPlugin = new AcceptFormatRequestValidatorPlugin();
        $acceptFormatRequestValidatorPlugin->validate($glueRequestTransfer);
    }
}
