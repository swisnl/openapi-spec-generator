<?php

namespace LaravelJsonApi\OpenApiSpec\Descriptors\Responses;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class FetchOne extends ResponseDescriptor
{
    protected bool $hasId = true;

    /**
     * {@inheritDoc}
     *
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     */
    public function response(): array
    {
        return [
          $this->ok(),
          ...$this->defaults(),
        ];
    }

    /**
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     */
    protected function data(): Schema
    {
        return $this->schemaBuilder->build($this->route)->objectId('data');
    }
}
