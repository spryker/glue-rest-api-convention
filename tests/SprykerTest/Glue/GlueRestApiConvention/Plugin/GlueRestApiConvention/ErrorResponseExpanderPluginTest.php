<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention\ErrorResponseExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueRestApiConvention
 * @group Plugin
 * @group GlueRestApiConvention
 * @group ErrorResponseExpanderPluginTest
 * Add your own group annotations below this line
 */
class ErrorResponseExpanderPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\GlueRestApiConvention\GlueRestApiConventionTester
     */
    protected $tester;

    /**
     * @var string
     */
    protected const ERROR_CODE_VALUE = '500';

    /**
     * @var string
     */
    protected const ERROR_CODE_KEY = 'code';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_VALUE = 'Internal Server Error';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_KEY = 'message';

    /**
     * @return void
     */
    public function testErrorResponseExpanderPlugin(): void
    {
        //Arrange
        $glueRequestTransfer = $this->tester->createGlueRequestTransfer();
        //Arrange
        $errorTransfer = (new GlueErrorTransfer())
            ->setCode(static::ERROR_CODE_VALUE)
            ->setMessage(static::ERROR_MESSAGE_VALUE);
        $glueResponseTransfer = (new GlueResponseTransfer())
            ->addError($errorTransfer);

        //Act
        $errorResponseExpanderPlugin = new ErrorResponseExpanderPlugin();
        $result = $errorResponseExpanderPlugin->expand($glueResponseTransfer, $glueRequestTransfer, []);

        //Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey(static::ERROR_CODE_KEY, $result);
        $this->assertSame(static::ERROR_CODE_VALUE, $result[static::ERROR_CODE_KEY]);
        $this->assertArrayHasKey(static::ERROR_MESSAGE_KEY, $result);
        $this->assertSame(static::ERROR_MESSAGE_VALUE, $result[static::ERROR_MESSAGE_KEY]);
    }
}
