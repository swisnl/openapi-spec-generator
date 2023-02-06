<?php

namespace LaravelJsonApi\OpenApiSpec\Descriptors\Actions\Relationship;

use LaravelJsonApi\OpenApiSpec\Descriptors\Actions\ActionDescriptor;

class Attach extends ActionDescriptor
{
    protected function summary(): string
    {
        return "Attach {$this->route->relationName()} relation";
    }
}
