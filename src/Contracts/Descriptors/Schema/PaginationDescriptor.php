<?php

namespace LaravelJsonApi\OpenApiSpec\Contracts\Descriptors\Schema;

use LaravelJsonApi\OpenApiSpec\Contracts\Descriptors\Descriptor;
use LaravelJsonApi\OpenApiSpec\Route;

interface PaginationDescriptor extends Descriptor
{
    /**
     * @param \LaravelJsonApi\OpenApiSpec\Route $route
     *
     * @return mixed
     */
    public function pagination(Route $route);
}
