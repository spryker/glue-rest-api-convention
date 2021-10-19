<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\RequestBuilder;

use ArrayObject;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;

class RequestRestResourceBuilder implements RequestRestResourceBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequest
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function build(GlueRequestTransfer $glueRequest): GlueRequestTransfer
    {
        $urlParts = $this->splitUrl($glueRequest);

        $resources = $this->extractResources($urlParts);
        $glueRequest->setResource(array_pop($resources));
        $glueRequest->setParentResources(new ArrayObject($resources));

        return $glueRequest;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequest
     *
     * @return array<string>
     */
    protected function splitUrl(GlueRequestTransfer $glueRequest): array
    {
        return explode('/', trim($glueRequest->getPath(), '\/'));
    }

    /**
     * @param array<string> $urlParts
     *
     * @return array<string, GlueResourceTransfer>
     */
    protected function extractResources(array $urlParts): array
    {
        $urlPartsCount = count($urlParts);
        $resources = [];
        $index = 0;

        while ($index < $urlPartsCount) {
            $type = $urlParts[$index];

            if ($type === '') {
                $index += 2;

                continue;
            }

            $resource = new GlueResourceTransfer();
            $resource->setType($type);
            $resource->setId($urlParts[$index + 1] ?? null);
            $resources[$type] = $resource;

            $index += 2;
        }

        return $resources;
    }
}
