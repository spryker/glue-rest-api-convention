<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention;

use Codeception\Test\Unit;
use Spryker\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention\RestApiResponseFormatterPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueRestApiConvention
 * @group Plugin
 * @group GlueRestApiConvention
 * @group RestApiResponseFormatterPluginTest
 * Add your own group annotations below this line
 */
class RestApiResponseFormatterPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\GlueRestApiConvention\GlueRestApiConventionTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testRestApiResponseFormatterPlugin(): void
    {
        //Arrange
        $glueRequestTransfer = $this->tester->createGlueRequestTransfer();
        $glueResponseTransfer = $this->tester->createGlueResponseTransfer();

        //Act
        $restApiResponseFormatterPlugin = new RestApiResponseFormatterPlugin();
        $glueResponseTransfer = $restApiResponseFormatterPlugin->build($glueResponseTransfer, $glueRequestTransfer);

        //Assert
        $this->assertSame($this->tester::RESPONSE_STATUS, $glueResponseTransfer->getStatus());
        $this->assertSame($this->tester::RESPONSE_CONTENT, $glueResponseTransfer->getContent());
    }
}
