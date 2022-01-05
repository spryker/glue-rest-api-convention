<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\Resource;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\RestResourceInterface;
use Spryker\Glue\GlueRestApiConvention\Exception\MissingRequestDataException;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

// TODO: will be removed when GlueApplication will be done
class RestApiResourceExecutor implements RestApiResourceExecutorInterface
{
    /**
     * @var string
     */
    protected const RESOURCE_ATTRIBUTES = 'attributes';
    /**
     * @var string
     */
    protected const RESOURCE_DATA = 'data';

    /**
     * @var string
     */
    public const RESOURCE_TYPE = 'type';

    /**
     * @param \Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\RestResourceInterface $resource
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */

    // TODO: will be moved to GlueApplication
    public function executeResource(RestResourceInterface $resource, GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        $glueResponseTransfer = new GlueResponseTransfer();

        $executableResource = $resource->getResource($glueRequestTransfer);

        if ($glueRequestTransfer->getContent()) {
            $transferClass = $resource->getResourceAttributesClassName();
            $resourceAttributesTransfer = new $transferClass();

            $parsedRequestBody = $this->extract($glueRequestTransfer);
            $resourceAttributesTransfer->fromArray($parsedRequestBody[static::RESOURCE_ATTRIBUTES], true);
            $glueRequestTransfer->getResource()->setAttributes($resourceAttributesTransfer);

            return call_user_func($resource->getResource($glueRequestTransfer), $resourceAttributesTransfer, $glueRequestTransfer, $glueResponseTransfer);
        }

        if ($glueRequestTransfer->getResource()->getId()) {
            return call_user_func($resource->getResource($glueRequestTransfer), $glueRequestTransfer->getResource()->getId(), $glueRequestTransfer, $glueResponseTransfer);
        }

        return call_user_func($resource->getResource($glueRequestTransfer), $glueRequestTransfer, $glueResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @throws \Spryker\Glue\GlueRestApiConvention\Exception\MissingRequestDataException
     *
     * @return array<mixed>
     */
    protected function extract(GlueRequestTransfer $glueRequestTransfer): array
    {
        $requestData = json_decode((string)$glueRequestTransfer->getContent());

        if (
            !isset($requestData[static::RESOURCE_DATA]) ||
            !isset($requestData[static::RESOURCE_DATA][static::RESOURCE_TYPE]) ||
            !isset($requestData[static::RESOURCE_DATA][static::RESOURCE_ATTRIBUTES])
        ) {
            throw new MissingRequestDataException('Wrong request content data.');
        }

        return $requestData[static::RESOURCE_DATA];
    }
}
