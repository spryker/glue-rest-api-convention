<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Bundles\GlueRestApiConvention\tests\SprykerTest\Glue\GlueRestApiConvention\ResponseBuilder\Expander;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueRestApiConvention\ResponseBuilder\Expander\ErrorExpander;

/**
 * Auto-generated group annotations
 *
 * @group Bundles
 * @group GlueRestApiConvention
 * @group tests
 * @group SprykerTest
 * @group Glue
 * @group GlueRestApiConvention
 * @group ResponseBuilder
 * @group Expander
 * @group ErrorExpanderTest
 * Add your own group annotations below this line
 */
class ErrorExpanderTest extends Unit
{
    /**
     * @var string
     */
    protected const ERROR_CODE = '404';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE = 'Not found!';

    /**
     * @return void
     */
    public function testErrorExpanderWithoutErrors(): void
    {
        //Arrange
        $glueResponseTransfer = new GlueResponseTransfer();

        //Act
        $errorExpander = new ErrorExpander();
        $result = $errorExpander->expandResponseData($glueResponseTransfer, []);

        //Assert
        $this->assertSame([], $result);
    }

    /**
     * @return void
     */
    public function testErrorExpander(): void
    {
        //Arrange
        $errorTransfer = (new GlueErrorTransfer())
            ->setCode(static::ERROR_CODE)
            ->setMessage(static::ERROR_MESSAGE);
        $glueResponseTransfer = (new GlueResponseTransfer())
            ->addError($errorTransfer);

        //Act
        $errorExpander = new ErrorExpander();
        $result = $errorExpander->expandResponseData($glueResponseTransfer, []);

        //Assert
        $this->assertSame(static::ERROR_CODE, $result['code']);
        $this->assertSame(static::ERROR_MESSAGE, $result['message']);
    }
}
