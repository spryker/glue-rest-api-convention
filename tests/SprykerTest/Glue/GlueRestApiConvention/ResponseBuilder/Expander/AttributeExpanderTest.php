<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Bundles\GlueRestApiConvention\tests\SprykerTest\Glue\GlueRestApiConvention\ResponseBuilder\Expander;

use Codeception\Test\Unit;
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
        $glueResponse = new GlueResponseTransfer();

        $expander = new AttributeExpander();
        $result = $expander->expandResponseData($glueResponse, []);

        $this->assertSame([], $result);
    }

    /**
     * @return void
     */
    public function testCamelCasedFieldNames(): void
    {
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
        $glueResponse->setAttributes($transferMock);

        $expander = new AttributeExpander();
        $result = $expander->expandResponseData($glueResponse, []);

        $this->assertSame($expectedData, $result);
    }
}
