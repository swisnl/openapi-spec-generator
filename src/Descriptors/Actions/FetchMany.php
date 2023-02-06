<?php

namespace LaravelJsonApi\OpenApiSpec\Descriptors\Actions;

class FetchMany extends ActionDescriptor
{
    protected function summary(): string
    {
        return "Get all {$this->route->name()}";
    }
}
