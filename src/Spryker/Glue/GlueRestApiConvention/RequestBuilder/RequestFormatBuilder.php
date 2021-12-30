<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\RequestBuilder;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Symfony\Component\HttpFoundation\Request;

class RequestFormatBuilder implements RequestFormatBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function extract(GlueRequestTransfer $glueRequestTransfer): GlueRequestTransfer
    {
        $httpRequest = $this->getRequest();

        if ($httpRequest->getContent()) {
            $glueRequestTransfer->setContent($httpRequest->getContent());
        }

        if (isset($httpRequest->headers) && $httpRequest->headers->all()) {
            $glueRequestTransfer->setMeta($httpRequest->headers->all());
            $headers = $glueRequestTransfer->getMeta();
            if (isset($headers['content-type'])) {
                $glueRequestTransfer->setRequestedFormat($headers['content-type'][0]);
            }
            if (isset($headers['accept'])) {
                $glueRequestTransfer->setAcceptedFormat(explode('/', $headers['accept'][0])[1]);
            }
        }

        $glueRequestTransfer->setPath($httpRequest->getPathInfo());

        return $glueRequestTransfer;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getRequest(): Request
    {
        return Request::createFromGlobals();
    }
}
