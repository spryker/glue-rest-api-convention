<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\RequestBuilder;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueVersionTransfer;

class RequestVersionBuilder implements RequestVersionBuilderInterface
{
    /**
     * @var string
     */
    protected const VERSION_REGULAR_EXPRESSION = '/(?:;\s(?:version=([\d]+)\.?([\d]+)?))/';

    /**
     * @var string
     */
    protected const VERSION_HEADER = 'content-type';

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequest
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function build(GlueRequestTransfer $glueRequest): GlueRequestTransfer
    {
        if (!isset($glueRequest->getMeta()[static::VERSION_HEADER])) {
            return $glueRequest->setVersion(null);
        }

        $versionParts = $this->extractVersionParts($glueRequest);

        if (empty($versionParts)) {
            return $glueRequest->setVersion(null);
        }

        $glueRequest->setVersion(
            (new GlueVersionTransfer())
                ->setMajor($versionParts[0])
                ->setMinor($versionParts[1]),
        );

        return $glueRequest;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequest
     *
     * @return array<int>
     */
    protected function extractVersionParts(GlueRequestTransfer $glueRequest): array
    {
        $contentType = $glueRequest->getMeta()[static::VERSION_HEADER];

        $versionParts = [];

        if (preg_match(static::VERSION_REGULAR_EXPRESSION, $contentType, $versionParts) !== 1) {
            return [];
        }

        array_shift($versionParts);

        if (count($versionParts) === 1) {
            $versionParts[] = 0;
        }

        return array_map('intval', $versionParts);
    }
}
