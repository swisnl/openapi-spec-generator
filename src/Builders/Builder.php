<?php

namespace LaravelJsonApi\OpenApiSpec\Builders;

use LaravelJsonApi\OpenApiSpec\Generator;

/**
 * Builders represent the hierarchy of the OpenApi Spec.
 * They use Descriptors to extract the needed information from the JSON:API
 * implementation and convert it to OpenAPI specs.
 */
abstract class Builder
{
    protected Generator $generator;

    public function __construct(Generator $generator)
    {
        $this->generator = $generator;
    }
}
