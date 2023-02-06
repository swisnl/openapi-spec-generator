<?php

namespace LaravelJsonApi\OpenApiSpec\Descriptors\Schema\Filters;

class Scope extends BooleanFilter
{
    protected function description(): string
    {
        return "Applies the {$this->filter->key()} scope.";
    }
}
