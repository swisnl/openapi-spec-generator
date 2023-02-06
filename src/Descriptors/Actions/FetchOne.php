<?php

namespace LaravelJsonApi\OpenApiSpec\Descriptors\Actions;

class FetchOne extends ActionDescriptor
{
    protected function summary(): string
    {
        return "Show one {$this->route->name(true)}";
    }
}
