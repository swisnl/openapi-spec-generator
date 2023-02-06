<?php

namespace LaravelJsonApi\OpenApiSpec\Descriptors\Actions;

class Store extends ActionDescriptor
{
    protected function summary(): string
    {
        return "Store one {$this->route->name(true)}";
    }
}
