<?php

namespace LaravelJsonApi\OpenApiSpec\Descriptors\Responses;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use LaravelJsonApi\Eloquent\Fields\Relations\ToMany;

class UpdateRelationship extends ResponseDescriptor
{
    protected bool $hasId = true;

    protected bool $validates = true;

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
        if ($this->route->relation() instanceof ToMany) {
            return Schema::array('data')
              ->items($this->schemaBuilder->build($this->route));
        }

        return $this->schemaBuilder->build($this->route)->objectId('data');
    }
}
