<?php

namespace LaravelJsonApi\OpenApiSpec\Descriptors\Actions;

class Update extends ActionDescriptor
{
    protected function summary(): string
    {
        return "Update one {$this->route->name(true)}";
    }
}
