<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\Formatter;

use Generated\Shared\Transfer\ResourceContextTransfer;

class RestApiSchemaParametersFormatter implements RestApiSchemaParametersFormatterInterface
{
    /**
     * @param array<mixed> $operation
     * @param \Generated\Shared\Transfer\ResourceContextTransfer $resourceContext
     *
     * @return array<mixed>
     */
    public function setOperationParameters(array $operation, ResourceContextTransfer $resourceContext): array
    {
        $operation = $this->setOperationParameter($operation, 'Page');
        $operation = $this->setOperationParameter($operation, 'Fields');
        $operation = $this->setOperationParameter($operation, 'Filter');

        return $operation;
    }

    /**
     * @param array<mixed> $formattedData
     *
     * @return array<mixed>
     */
    public function setComponentParameters(array $formattedData): array
    {
        $formattedData = $this->formatPageParameter($formattedData);
        $formattedData = $this->formatFieldsParameter($formattedData);
        $formattedData = $this->formatFilterParameter($formattedData);

        return $formattedData;
    }

    /**
     * @param array<mixed> $operation
     * @param string $parameterName
     *
     * @return array<mixed>
     */
    protected function setOperationParameter(array $operation, string $parameterName): array
    {
        $parameters = $this->mapComponentParametersWithRef($operation);
        $parameterRef = '#/components/parameters/' . $parameterName;
        if (!in_array($parameterRef, $parameters)) {
            $operation['parameters'][] = ['$ref' => $parameterRef];
        }

        return $operation;
    }

    /**
     * @param array<mixed> $operation
     *
     * @return array<string|null>
     */
    protected function mapComponentParametersWithRef(array $operation): array
    {
        $params = [];
        foreach ($operation['parameters'] ?? [] as $parameter) {
            if (isset($parameter['$ref'])) {
                $params[] = $parameter['$ref'];
            }
        }

        return $params;
    }

    /**
     * @param array<mixed> $formattedData
     *
     * @return array<mixed>
     */
    protected function formatPageParameter(array $formattedData): array
    {
        $page = [
            'name' => 'page',
            'in' => 'query',
            'required' => false,
            'description' => 'Parameter is used to limit requested items.',
            'style' => 'deepObject',
            'explode' => true,
            'schema' => [
                'type' => 'object',
                'properties' => [
                    'offset' => [
                        'type' => 'integer',
                        'description' => 'The number of items to skip before starting to collect the result set.',
                    ],
                    'limit' => [
                        'type' => 'integer',
                        'description' => 'The numbers of items to return.',
                    ],
                ],
                'example' => [
                    'offset' => 1,
                    'limit' => 10,
                ],
            ],
        ];

        return $this->setComponentParametersToOpenApi($formattedData, $page, 'Page');
    }

    /**
     * @param array<mixed> $formattedData
     *
     * @return array<mixed>
     */
    protected function formatFieldsParameter(array $formattedData): array
    {
        $fields = [
            'name' => 'fields',
            'in' => 'query',
            'required' => false,
            'description' => 'Parameter is used to extract specified items\` fields.',
            'style' => 'deepObject',
            'explode' => true,
            'schema' => [
                'type' => 'object',
                'properties' => [
                    'resourceName' => [
                        'type' => 'array',
                        'description' => 'The name of resource.',
                        'items' => [
                            'type' => 'string',
                        ],
                    ],
                ],
                'example' => [
                    'people' => 'name,last-name',
                ],
            ],
        ];

        return $this->setComponentParametersToOpenApi($formattedData, $fields, 'Fields');
    }

    /**
     * @param array<mixed> $formattedData
     *
     * @return array<mixed>
     */
    protected function formatFilterParameter(array $formattedData): array
    {
        $filter = [
            'name' => 'filter',
            'in' => 'query',
            'required' => false,
            'description' => 'Parameter is used to sort items by specified values.',
            'style' => 'deepObject',
            'explode' => true,
            'schema' => [
                'type' => 'object',
                'properties' => [
                    'resource.propertyName' => [
                        'description' => 'test',
                    ],
                ],
                'example' => [
                    'wishlists.name' => 'Test',
                    'wishlists.quantity' => 1,
                ],
            ],
        ];

        return $this->setComponentParametersToOpenApi($formattedData, $filter, 'Filter');
    }

    /**
     * @param array<mixed> $formattedData
     * @param array<string, mixed> $parameterData
     * @param string $parameterName
     *
     * @return array<mixed>
     */
    protected function setComponentParametersToOpenApi(
        array $formattedData,
        array $parameterData,
        string $parameterName
    ): array {
        if (isset($formattedData['components'])) {
            $formattedData['components']['parameters'] = array_merge(
                $formattedData['components']['parameters'],
                [$parameterName => $parameterData],
            );
        }

        return $formattedData;
    }
}
