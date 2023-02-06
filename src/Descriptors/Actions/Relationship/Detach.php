<?php

namespace LaravelJsonApi\OpenApiSpec\Descriptors\Actions\Relationship;

class Detach extends Attach
{
    protected function summary(): string
    {
        return "Detach {$this->route->relationName()} relation";
    }
}
