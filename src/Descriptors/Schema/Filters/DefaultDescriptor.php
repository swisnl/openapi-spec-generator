<?php

namespace LaravelJsonApi\OpenApiSpec\Descriptors\Schema\Filters;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema as OASchema;

class DefaultDescriptor extends FilterDescriptor
{
    /**
     * {@inheritDoc}
     */
    public function filter(): array
    {
        return [
            Parameter::query()
                ->name("filter[{$this->filter->key()}]")
                ->description($this->description())
                ->required(false)
                ->allowEmptyValue(false)
                ->schema(OASchema::string()),
        ];
    }

    protected function description(): string
    {
        return 'Filters the records';
    }
}
