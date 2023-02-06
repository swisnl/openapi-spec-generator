<?php

namespace LaravelJsonApi\OpenApiSpec\Descriptors\Actions\Relationship;

class Update extends Attach
{
    protected function summary(): string
    {
        return "Update {$this->route->relationName()} relation";
    }
}
