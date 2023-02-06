<?php

namespace LaravelJsonApi\OpenApiSpec\Descriptors;

use LaravelJsonApi\OpenApiSpec\Contracts\Descriptors\Descriptor as DescriptorContract;
use LaravelJsonApi\OpenApiSpec\Generator;

abstract class Descriptor implements DescriptorContract
{
    protected Generator $generator;

    public function __construct(Generator $generator)
    {
        $this->generator = $generator;
    }
}
