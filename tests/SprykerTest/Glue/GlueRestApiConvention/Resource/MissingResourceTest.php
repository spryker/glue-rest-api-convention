<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Bundles\GlueRestApiConvention\tests\SprykerTest\Glue\GlueRestApiConvention\Resource;

use Codeception\Test\Unit;
use Spryker\Glue\GlueRestApiConvention\Resource\MissingResource;

/**
 * Auto-generated group annotations
 *
 * @group Bundles
 * @group GlueRestApiConvention
 * @group tests
 * @group SprykerTest
 * @group Glue
 * @group GlueRestApiConvention
 * @group Resource
 * @group MissingResourceTest
 * Add your own group annotations below this line
 */
class MissingResourceTest extends Unit
{
    /**
     * @return void
     */
    public function testNeverReturnsMatchingResourceCollection(): void
    {
        $missingResource = new MissingResource('400', 'error');
        $this->assertSame('400', $missingResource->getStatusCode());
        $this->assertSame('error', $missingResource->getError());
        $this->assertNull($missingResource->getMatchingResourceCollection());
    }
}
