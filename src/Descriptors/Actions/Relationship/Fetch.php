<?php

namespace LaravelJsonApi\OpenApiSpec\Descriptors\Actions\Relationship;

use LaravelJsonApi\OpenApiSpec\Descriptors\Actions\ActionDescriptor;

class Fetch extends ActionDescriptor
{
    protected function summary(): string
    {
        return "Show {$this->route->relationName()} relation";
    }
}
