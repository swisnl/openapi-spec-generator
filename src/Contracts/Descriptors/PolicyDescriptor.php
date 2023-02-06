<?php

namespace LaravelJsonApi\OpenApiSpec\Contracts\Descriptors;

use LaravelJsonApi\OpenApiSpec\Route;

interface PolicyDescriptor extends Descriptor
{
    /**
     * @param \LaravelJsonApi\OpenApiSpec\Route $route
     *
     * @return bool
     */
    public function anonymous(Route $route): bool;
}
