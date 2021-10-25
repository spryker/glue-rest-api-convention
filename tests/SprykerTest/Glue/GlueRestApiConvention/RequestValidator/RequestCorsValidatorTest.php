<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueRestApiConvention\RequestValidator;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use PHPUnit\Framework\MockObject\Rule\InvokedCount as InvokedCountMatcher;
use Spryker\Glue\GlueRestApiConvention\Cors\CorsConstants;
use Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionConfig;
use Spryker\Glue\GlueRestApiConvention\RequestValidator\RequestCorsValidator;
use Spryker\Glue\GlueRestApiConvention\Router\ResourceRouteCollection;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResourceRoutePluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueRestApiConvention
 * @group RequestValidator
 * @group RequestCorsValidatorTest
 * Add your own group annotations below this line
 */
class RequestCorsValidatorTest extends Unit
{
    /**
     * @var string
     */
    protected const ALLOWED_HEADER = 'allowed-header';

    /**
     * @var string
     */
    protected const OTHER_ALLOWED_HEADER = 'other-allowed-header';

    /**
     * @return void
     */
    public function testEmptyCorsMethodHeaderWillSkipValidation(): void
    {
        $expectedCorsHeaders = [
            CorsConstants::HEADER_ACCESS_CONTROL_REQUEST_METHOD => null,
            CorsConstants::HEADER_ACCESS_CONTROL_REQUEST_HEADERS => null,
        ];
        $resourceRoutePluginMock = $this->createMock(ResourceRoutePluginInterface::class);
        $resourceRoutePluginMock->expects($this->never())
            ->method('configure')
            ->willReturn(new ResourceRouteCollection());
        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setMeta($expectedCorsHeaders);
        $corsValidator = new RequestCorsValidator($this->createConfigMock($this->never()));

        $result = $corsValidator->validate($glueRequest, $resourceRoutePluginMock);
        $this->assertInstanceOf(GlueRequestValidationTransfer::class, $result);
        $this->assertTrue($result->getIsValid());
    }

    /**
     * @return void
     */
    public function testMethodIsNotAllowedWillReturnBadRequest(): void
    {
        $expectedCorsHeaders = [
            CorsConstants::HEADER_ACCESS_CONTROL_REQUEST_METHOD => 'GET',
            CorsConstants::HEADER_ACCESS_CONTROL_REQUEST_HEADERS => null,
        ];
        $resourceRoutePluginMock = $this->createMock(ResourceRoutePluginInterface::class);
        $resourceRoutePluginMock->expects($this->once())
            ->method('configure')
            ->willReturn((new ResourceRouteCollection())->addDelete('delete'));
        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setMeta($expectedCorsHeaders);
        $corsValidator = new RequestCorsValidator($this->createConfigMock($this->never()));

        $result = $corsValidator->validate($glueRequest, $resourceRoutePluginMock);
        $this->assertInstanceOf(GlueRequestValidationTransfer::class, $result);
        $this->assertFalse($result->getIsValid());
    }

    /**
     * @return void
     */
    public function testMethodOptionsIsAlwaysAllowed(): void
    {
        $expectedCorsHeaders = [
            CorsConstants::HEADER_ACCESS_CONTROL_REQUEST_METHOD => ResourceRouteCollection::METHOD_OPTIONS,
            CorsConstants::HEADER_ACCESS_CONTROL_REQUEST_HEADERS => null,
        ];
        $resourceRoutePluginMock = $this->createMock(ResourceRoutePluginInterface::class);
        $resourceRoutePluginMock->expects($this->never())
            ->method('configure')
            ->willReturn((new ResourceRouteCollection())->addGet('get'));
        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setMeta($expectedCorsHeaders);
        $corsValidator = new RequestCorsValidator($this->createConfigMock($this->never()));

        $result = $corsValidator->validate($glueRequest, $resourceRoutePluginMock);
        $this->assertInstanceOf(GlueRequestValidationTransfer::class, $result);
        $this->assertTrue($result->getIsValid());
    }

    /**
     * @return void
     */
    public function testGetWithNoError(): void
    {
        $expectedCorsHeaders = [
            CorsConstants::HEADER_ACCESS_CONTROL_REQUEST_METHOD => ResourceRouteCollection::METHOD_GET,
            CorsConstants::HEADER_ACCESS_CONTROL_REQUEST_HEADERS => static::ALLOWED_HEADER,
        ];
        $result = $this->validateRequest($expectedCorsHeaders);
        $this->assertTrue($result->getIsValid());
    }

    /**
     * @return void
     */
    public function testGetCollectionWillBeHandledAsGet(): void
    {
        $expectedCorsHeaders = [
            CorsConstants::HEADER_ACCESS_CONTROL_REQUEST_METHOD => ResourceRouteCollection::METHOD_GET,
            CorsConstants::HEADER_ACCESS_CONTROL_REQUEST_HEADERS => static::ALLOWED_HEADER,
        ];
        $resourceRoutePluginMock = $this->createMock(ResourceRoutePluginInterface::class);
        $resourceRoutePluginMock->expects($this->once())
            ->method('configure')
            ->willReturn((new ResourceRouteCollection())->addGetCollection('collection'));
        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setMeta($expectedCorsHeaders);
        $corsValidator = new RequestCorsValidator($this->createConfigMock());

        $result = $corsValidator->validate($glueRequest, $resourceRoutePluginMock);
        $this->assertInstanceOf(GlueRequestValidationTransfer::class, $result);
        $this->assertTrue($result->getIsValid());
    }

    /**
     * @return void
     */
    public function testDeniedHeadersWillReturnBadRequest(): void
    {
        $expectedCorsHeaders = [
            CorsConstants::HEADER_ACCESS_CONTROL_REQUEST_METHOD => ResourceRouteCollection::METHOD_GET,
            CorsConstants::HEADER_ACCESS_CONTROL_REQUEST_HEADERS => implode(', ', [static::ALLOWED_HEADER, 'non-allowed-header']),
        ];

        $result = $this->validateRequest($expectedCorsHeaders);
        $this->assertFalse($result->getIsValid());
    }

    /**
     * @return void
     */
    public function testAllHeadersAreAllowed(): void
    {
        $expectedCorsHeaders = [
            CorsConstants::HEADER_ACCESS_CONTROL_REQUEST_METHOD => ResourceRouteCollection::METHOD_GET,
            CorsConstants::HEADER_ACCESS_CONTROL_REQUEST_HEADERS => implode(', ', [static::ALLOWED_HEADER, static::OTHER_ALLOWED_HEADER]),
        ];

        $result = $this->validateRequest($expectedCorsHeaders);
        $this->assertTrue($result->getIsValid());
    }

    /**
     * @return void
     */
    public function testEmptyHeadersAreAllowed(): void
    {
        $expectedCorsHeaders = [
            CorsConstants::HEADER_ACCESS_CONTROL_REQUEST_METHOD => ResourceRouteCollection::METHOD_GET,
            CorsConstants::HEADER_ACCESS_CONTROL_REQUEST_HEADERS => '',
        ];

        $resourceRoutePluginMock = $this->createMock(ResourceRoutePluginInterface::class);
        $resourceRoutePluginMock->expects($this->once())
            ->method('configure')
            ->willReturn((new ResourceRouteCollection())->addGet('get'));
        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setMeta($expectedCorsHeaders);
        $corsValidator = new RequestCorsValidator($this->createConfigMock($this->never()));

        $result = $corsValidator->validate($glueRequest, $resourceRoutePluginMock);
        $this->assertInstanceOf(GlueRequestValidationTransfer::class, $result);
        $this->assertTrue($result->getIsValid());
    }

    /**
     * @param array $expectedCorsHeaders
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    protected function validateRequest(array $expectedCorsHeaders): GlueRequestValidationTransfer
    {
        $resourceRoutePluginMock = $this->createMock(ResourceRoutePluginInterface::class);
        $resourceRoutePluginMock->expects($this->once())
            ->method('configure')
            ->willReturn((new ResourceRouteCollection())->addGet('get'));
        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setMeta($expectedCorsHeaders);
        $corsValidator = new RequestCorsValidator($this->createConfigMock());

        $result = $corsValidator->validate($glueRequest, $resourceRoutePluginMock);
        $this->assertInstanceOf(GlueRequestValidationTransfer::class, $result);

        return $result;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\Rule\InvokedCount|null $invokedCount
     *
     * @return mixed|\PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionConfig
     */
    protected function createConfigMock(?InvokedCountMatcher $invokedCount = null)
    {
        if ($invokedCount === null) {
            $invokedCount = $this->once();
        }

        $configMock = $this->createMock(GlueRestApiConventionConfig::class);
        $configMock->expects($invokedCount)
            ->method('getCorsAllowedHeaders')
            ->willReturn([static::ALLOWED_HEADER, static::OTHER_ALLOWED_HEADER]);

        return $configMock;
    }
}
