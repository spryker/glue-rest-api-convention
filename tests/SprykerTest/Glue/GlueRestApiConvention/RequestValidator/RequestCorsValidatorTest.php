<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueRestApiConvention\RequestValidator;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Generated\Shared\Transfer\GlueResourceMethodCollectionTransfer;
use Generated\Shared\Transfer\GlueResourceMethodConfigurationTransfer;
use PHPUnit\Framework\MockObject\Rule\InvokedCount as InvokedCountMatcher;
use Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionConfig;
use Spryker\Glue\GlueRestApiConvention\RequestValidator\RequestCorsValidator;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\RestResourceInterface;
use Symfony\Component\HttpFoundation\Request;

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
     * @var string
     */
    protected const HEADER_ACCESS_CONTROL_REQUEST_HEADERS = 'access-control-request-headers';

    /**
     * @var string
     */
    protected const HEADER_ACCESS_CONTROL_REQUEST_METHOD = 'access-control-request-method';

    /**
     * @return void
     */
    public function testEmptyCorsMethodHeaderWillSkipValidation(): void
    {
        $expectedCorsHeaders = [
            static::HEADER_ACCESS_CONTROL_REQUEST_METHOD => null,
            static::HEADER_ACCESS_CONTROL_REQUEST_HEADERS => null,
        ];
        $restResourcePluginMock = $this->createMock(RestResourceInterface::class);
        $restResourcePluginMock->expects($this->never())
            ->method('getDeclaredMethods')
            ->willReturn(new GlueResourceMethodCollectionTransfer());
        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setMeta($expectedCorsHeaders);
        $corsValidator = new RequestCorsValidator($this->createConfigMock($this->never()));

        $result = $corsValidator->validate($glueRequest, $restResourcePluginMock);
        $this->assertInstanceOf(GlueRequestValidationTransfer::class, $result);
        $this->assertTrue($result->getIsValid());
    }

    /**
     * @return void
     */
    public function testMethodIsNotAllowedWillReturnBadRequest(): void
    {
        $expectedCorsHeaders = [
            static::HEADER_ACCESS_CONTROL_REQUEST_METHOD => 'GET',
            static::HEADER_ACCESS_CONTROL_REQUEST_HEADERS => null,
        ];
        $restResourcePluginMock = $this->createMock(RestResourceInterface::class);
        $restResourcePluginMock->expects($this->once())
            ->method('getDeclaredMethods')
            ->willReturn((new GlueResourceMethodCollectionTransfer())->setDelete(
                (new GlueResourceMethodConfigurationTransfer())
                ->setAction('deleteAction'),
            ));
        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setMeta($expectedCorsHeaders);
        $corsValidator = new RequestCorsValidator($this->createConfigMock($this->never()));

        $result = $corsValidator->validate($glueRequest, $restResourcePluginMock);
        $this->assertInstanceOf(GlueRequestValidationTransfer::class, $result);
        $this->assertFalse($result->getIsValid());
    }

    /**
     * @return void
     */
    public function testMethodOptionsIsAlwaysAllowed(): void
    {
        $expectedCorsHeaders = [
            static::HEADER_ACCESS_CONTROL_REQUEST_METHOD => Request::METHOD_OPTIONS,
            static::HEADER_ACCESS_CONTROL_REQUEST_HEADERS => null,
        ];
        $restResourcePluginMock = $this->createMock(RestResourceInterface::class);
        $restResourcePluginMock->expects($this->never())
            ->method('getDeclaredMethods')
            ->willReturn((new GlueResourceMethodCollectionTransfer())->setGet(
                (new GlueResourceMethodConfigurationTransfer())
                    ->setAction('getAction'),
            ));
        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setMeta($expectedCorsHeaders);
        $corsValidator = new RequestCorsValidator($this->createConfigMock($this->never()));

        $result = $corsValidator->validate($glueRequest, $restResourcePluginMock);
        $this->assertInstanceOf(GlueRequestValidationTransfer::class, $result);
        $this->assertTrue($result->getIsValid());
    }

    /**
     * @return void
     */
    public function testGetWithNoError(): void
    {
        $expectedCorsHeaders = [
            static::HEADER_ACCESS_CONTROL_REQUEST_METHOD => Request::METHOD_GET,
            static::HEADER_ACCESS_CONTROL_REQUEST_HEADERS => static::ALLOWED_HEADER,
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
            static::HEADER_ACCESS_CONTROL_REQUEST_METHOD => Request::METHOD_GET,
            static::HEADER_ACCESS_CONTROL_REQUEST_HEADERS => static::ALLOWED_HEADER,
        ];
        $restResourcePluginMock = $this->createMock(RestResourceInterface::class);
        $restResourcePluginMock->expects($this->once())
            ->method('getDeclaredMethods')
            ->willReturn((new GlueResourceMethodCollectionTransfer())->setGetCollection(
                (new GlueResourceMethodConfigurationTransfer())
                    ->setAction('getCollectionAction'),
            ));
        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setMeta($expectedCorsHeaders);
        $corsValidator = new RequestCorsValidator($this->createConfigMock());

        $result = $corsValidator->validate($glueRequest, $restResourcePluginMock);
        $this->assertInstanceOf(GlueRequestValidationTransfer::class, $result);
        $this->assertTrue($result->getIsValid());
    }

    /**
     * @return void
     */
    public function testDeniedHeadersWillReturnBadRequest(): void
    {
        $expectedCorsHeaders = [
            static::HEADER_ACCESS_CONTROL_REQUEST_METHOD => Request::METHOD_GET,
            static::HEADER_ACCESS_CONTROL_REQUEST_HEADERS => implode(', ', [static::ALLOWED_HEADER, 'non-allowed-header']),
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
            static::HEADER_ACCESS_CONTROL_REQUEST_METHOD => Request::METHOD_GET,
            static::HEADER_ACCESS_CONTROL_REQUEST_HEADERS => implode(', ', [static::ALLOWED_HEADER, static::OTHER_ALLOWED_HEADER]),
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
            static::HEADER_ACCESS_CONTROL_REQUEST_METHOD => Request::METHOD_GET,
            static::HEADER_ACCESS_CONTROL_REQUEST_HEADERS => '',
        ];

        $restResourcePluginMock = $this->createMock(RestResourceInterface::class);
        $restResourcePluginMock->expects($this->once())
            ->method('getDeclaredMethods')
            ->willReturn((new GlueResourceMethodCollectionTransfer())->setGet(
                (new GlueResourceMethodConfigurationTransfer())
                    ->setAction('getAction'),
            ));
        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setMeta($expectedCorsHeaders);
        $corsValidator = new RequestCorsValidator($this->createConfigMock($this->never()));

        $result = $corsValidator->validate($glueRequest, $restResourcePluginMock);
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
        $restResourcePluginMock = $this->createMock(RestResourceInterface::class);
        $restResourcePluginMock->expects($this->once())
            ->method('getDeclaredMethods')
            ->willReturn((new GlueResourceMethodCollectionTransfer())->setGet(
                (new GlueResourceMethodConfigurationTransfer())
                    ->setAction('getAction'),
            ));
        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setMeta($expectedCorsHeaders);
        $corsValidator = new RequestCorsValidator($this->createConfigMock());

        $result = $corsValidator->validate($glueRequest, $restResourcePluginMock);
        $this->assertInstanceOf(GlueRequestValidationTransfer::class, $result);

        return $result;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\Rule\InvokedCount|null $invokedCount
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionConfig|mixed
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
