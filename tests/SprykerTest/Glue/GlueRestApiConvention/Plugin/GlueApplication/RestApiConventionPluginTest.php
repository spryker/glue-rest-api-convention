<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueRestApiConvention\Plugin\GlueApplication;

use Codeception\Test\Unit;
use Spryker\Glue\GlueApplication\ApiApplication\Type\ApiConventionPluginInterface;
use Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionConfig;
use Spryker\Glue\GlueRestApiConvention\Plugin\GlueApplication\RestApiConventionPlugin;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\RestResourceInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueRestApiConvention
 * @group Plugin
 * @group GlueApplication
 * @group RestApiConventionPluginTest
 * Add your own group annotations below this line
 */
class RestApiConventionPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\GlueRestApiConvention\GlueRestApiConventionTester
     */
    protected $tester;

    /**
     * @return \Spryker\Glue\GlueApplication\ApiApplication\Type\ApiConventionPluginInterface
     */
    public function createRestApiConventionPlugin(): ApiConventionPluginInterface
    {
        return new RestApiConventionPlugin();
    }

    /**
     * @return void
     */
    public function testRestApiConventionPluginIsApplicable(): void
    {
        //Arrange
        $glueRequestTransfer = $this->tester->createGlueRequestTransfer();

        //Act
        $restApiConventionPlugin = $this->createRestApiConventionPlugin();
        $isApplicable = $restApiConventionPlugin->isApplicable($glueRequestTransfer);

        //Assert
        $this->assertTrue($isApplicable);
    }

    /**
     * @return void
     */
    public function testRestApiConventionPluginGetName(): void
    {
        //Act
        $restApiConventionPlugin = $this->createRestApiConventionPlugin();
        $restApiConventionName = $restApiConventionPlugin->getName();

        //Assert
        $this->assertSame(GlueRestApiConventionConfig::CONVENTION_REST_API, $restApiConventionName);
    }

    /**
     * @return void
     */
    public function testRestApiConventionPluginGetResourceType(): void
    {
        //Act
        $restApiConventionPlugin = $this->createRestApiConventionPlugin();
        $restApiApiConventionResourceType = $restApiConventionPlugin->getResourceType();

        //Assert
        $this->assertSame(RestResourceInterface::class, $restApiApiConventionResourceType);
    }

    /**
     * @return void
     */
    public function testRestApiConventionPluginBuildRequest(): void
    {
        //Arrange
        $glueRequestTransfer = $this->tester->createGlueRequestTransfer();

        //Act
        $restApiConventionPlugin = $this->createRestApiConventionPlugin();
        $restApiApiConventionName = $restApiConventionPlugin->buildRequest($glueRequestTransfer);
    }

    /**
     * @return void
     */
    public function testRestApiConventionPluginValidateRequest(): void
    {
        //Arrange
        $glueRequestTransfer = $this->tester->createGlueRequestTransfer();

        //Act
        $restApiConventionPlugin = $this->createRestApiConventionPlugin();
        $restApiApiConventionName = $restApiConventionPlugin->validateRequest($glueRequestTransfer);
    }

    /**
     * @return void
     */
    public function testRestApiConventionPluginValidateRequestAfterRouting(): void
    {
        //Arrange
        $glueRequestTransfer = $this->tester->createGlueRequestTransfer();
        $restApiResourceInterfaceMock = $this->getMockBuilder(RestResourceInterface::class)->getMock();

        //Act
        $restApiConventionPlugin = $this->createRestApiConventionPlugin();
        $restApiApiConventionName = $restApiConventionPlugin->validateRequestAfterRouting($glueRequestTransfer, $restApiResourceInterfaceMock);
    }

    /**
     * @return void
     */
    public function testRestApiConventionPluginFormatResponse(): void
    {
        //Arrange
        $glueRequestTransfer = $this->tester->createGlueRequestTransfer();
        $glueResponseTransfer = $this->tester->createGlueResponseTransfer();

        //Act
        $restApiConventionPlugin = $this->createRestApiConventionPlugin();
        $restApiApiConventionName = $restApiConventionPlugin->formatResponse($glueResponseTransfer, $glueRequestTransfer);
    }
}
