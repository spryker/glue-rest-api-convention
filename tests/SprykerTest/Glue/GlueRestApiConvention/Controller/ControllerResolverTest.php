<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueRestApiConvention\Controller;

use Codeception\Test\Unit;
use Spryker\Glue\GlueRestApiConvention\Controller\ControllerResolver;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueRestApiConvention
 * @group Controller
 * @group ControllerResolverTest
 * Add your own group annotations below this line
 */
class ControllerResolverTest extends Unit
{
    /**
     * @return void
     */
    public function testClassNamePattern(): void
    {
        $controllerResolver = new ControllerResolver();
        $this->assertSame(
            '\\%namespace%\\Glue\\%bundle%%codeBucket%\\Controller\\%controller%',
            $controllerResolver->getClassPattern(),
        );
    }
}
