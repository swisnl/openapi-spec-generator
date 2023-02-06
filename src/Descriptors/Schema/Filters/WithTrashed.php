<?php

namespace LaravelJsonApi\OpenApiSpec\Descriptors\Schema\Filters;

use LaravelJsonApi\Eloquent\Filters\OnlyTrashed;

class WithTrashed extends BooleanFilter
{
    protected function description(): string
    {
        return $this->filter instanceof OnlyTrashed ? 'Show only trashed records.' : 'Include trashed records';
    }
}
