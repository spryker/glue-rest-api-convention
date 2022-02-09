<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Bundles\GlueRestApiConvention\tests\SprykerTest\Glue\GlueRestApiConvention\ResponseBuilder\Expander;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueRestApiConvention\ResponseBuilder\Expander\AttributeExpander;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

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
 * @group AttributeExpanderTest
 * Add your own group annotations below this line
 */
class AttributeExpanderTest extends Unit
{
    /**
     * @return void
     */
    public function testWithoutAttributes(): void
    {
        //Arrange
        $glueResponse = new GlueResponseTransfer();

        //Act
        $expander = new AttributeExpander();
        $result = $expander->expandResponseData($glueResponse, []);

        //Assert
        $this->assertSame([], $result);
    }

    /**
     * @return void
     */
    public function testCamelCasedFieldNames(): void
    {
        //Arrange
        $expectedData = ['greetingMessage' => 'helloWorld'];
        $glueResponse = new GlueResponseTransfer();
        $transferMock = $this->createMock(AbstractTransfer::class);
        $transferMock->expects($this->once())
            ->method('toArray')
            ->willReturnCallback(function (bool $isRecursive, bool $isCamelCase) use ($expectedData): array {
                $this->assertTrue($isRecursive);
                $this->assertTrue($isCamelCase);

                return $expectedData;
            });
        $glueResponse->setResources(new ArrayObject([(new GlueResourceTransfer())->setAttributes($transferMock)]));

        //Act
        $expander = new AttributeExpander();
        $result = $expander->expandResponseData($glueResponse, []);

        //Assert
        $this->assertSame($expectedData, $result);
    }
}
