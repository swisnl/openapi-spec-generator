<?php

namespace LaravelJsonApi\OpenApiSpec\Contracts\Descriptors\Schema;

use LaravelJsonApi\OpenApiSpec\Contracts\Descriptors\Descriptor;
use LaravelJsonApi\OpenApiSpec\Route;

interface SortablesDescriptor extends Descriptor
{
    /**
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter[]
     */
    public function sortables(Route $route): array;
}
