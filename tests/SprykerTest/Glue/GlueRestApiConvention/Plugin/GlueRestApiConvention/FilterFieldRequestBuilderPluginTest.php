<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueFilterTransfer;
use Spryker\Glue\GlueRestApiConvention\Plugin\GlueApplication\FilterFieldRequestBuilderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueRestApiConvention
 * @group Plugin
 * @group GlueRestApiConvention
 * @group FilterFieldRequestBuilderPluginTest
 * Add your own group annotations below this line
 */
class FilterFieldRequestBuilderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const FIELD_NAME = 'name';

    /**
     * @var \SprykerTest\Glue\GlueRestApiConvention\GlueRestApiConventionTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFilterFieldRequestBuilderPlugin(): void
    {
        //Arrange
        $glueRequestTransfer = $this->tester->createGlueRequestTransfer();

        //Act
        $filterFieldRequestBuilderPlugin = new FilterFieldRequestBuilderPlugin();
        $glueRequestTransfer = $filterFieldRequestBuilderPlugin->build($glueRequestTransfer);

        //Assert
        $this->assertCount(1, $glueRequestTransfer->getFilters());
        $firstFilter = $glueRequestTransfer->getFilters()->offsetGet(0);
        $this->assertInstanceOf(GlueFilterTransfer::class, $firstFilter);
        $this->assertSame(static::FIELD_NAME, $firstFilter->getField());
    }
}
