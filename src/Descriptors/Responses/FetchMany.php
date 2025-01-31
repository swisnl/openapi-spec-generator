<?php

namespace LaravelJsonApi\OpenApiSpec\Descriptors\Responses;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class FetchMany extends ResponseDescriptor
{
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
        return Schema::array('data')
            ->items($this->schemaBuilder->build($this->route));
    }
}
