<?php

namespace LaravelJsonApi\OpenApiSpec\Descriptors\Schema\Filters;

class Has extends BooleanFilter
{
    protected function description(): string
    {
        return "Only includes records that have {$this->filter->key()}.";
    }
}
