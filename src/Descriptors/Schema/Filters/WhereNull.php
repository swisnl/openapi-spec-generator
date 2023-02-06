<?php

namespace LaravelJsonApi\OpenApiSpec\Descriptors\Schema\Filters;

use LaravelJsonApi\Eloquent\Filters\WhereNotNull;

class WhereNull extends BooleanFilter
{
    protected function description(): string
    {
        return $this->filter instanceof WhereNotNull ? "Only includes records where {$this->filter->key()} is not null." : "Only includes records where {$this->filter->key()} is null.";
    }
}
