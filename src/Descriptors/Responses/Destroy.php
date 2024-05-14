<?php

namespace LaravelJsonApi\OpenApiSpec\Descriptors\Responses;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class Destroy extends ResponseDescriptor
{
    protected bool $hasId = true;

    /**
     * {@inheritDoc}
     */
    public function response(): array
    {
        return [
            $this->noContent(),
            ...$this->defaults(),
        ];
    }

    /**
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     */
    protected function data(): Schema
    {
        return $this->schemaBuilder->build($this->route);
    }
}
