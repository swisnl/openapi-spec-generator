<?php

namespace LaravelJsonApi\OpenApiSpec\Descriptors\Schema\Filters;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Example;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use LaravelJsonApi\Core\Resources\JsonApiResource;
use LaravelJsonApi\Eloquent\Filters\WhereIdNotIn;

class WhereIdIn extends FilterDescriptor
{
    /**
     * {@inheritDoc}
     */
    public function filter(): array
    {
        $key = $this->filter->key();
        $examples = collect($this->generator->resources()
          ->resources($this->route->schema()::model()))
          ->map(function (JsonApiResource $resource) {
              $id = $resource->id();

              return Example::create($id)->value([$id]);
          })
          ->toArray();

        return [
          Parameter::query()
            ->name("filter[{$key}]")
            ->description($this->description())
            ->required(false)
            ->allowEmptyValue(false)
            ->schema(Schema::array()->items(Schema::string())->default([]))
            ->examples(Example::create('empty')->value([]), ...$examples)
            ->style('form')
            ->explode(false),
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function description(): string
    {
        return $this->filter instanceof WhereIdNotIn ?
          'A list of ids to exclude by.' :
          'A list of ids to filter by.';
    }
}
