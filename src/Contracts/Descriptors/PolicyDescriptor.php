<?php

namespace LaravelJsonApi\OpenApiSpec\Contracts\Descriptors;

use LaravelJsonApi\OpenApiSpec\Route;

interface PolicyDescriptor extends Descriptor
{
    public function anonymous(Route $route): bool;
}
