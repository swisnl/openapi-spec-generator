<?php

namespace LaravelJsonApi\OpenApiSpec\Descriptors\Actions;

class Destroy extends ActionDescriptor
{
    protected function summary(): string
    {
        return "Destroy one {$this->route->name(true)}";
    }
}
