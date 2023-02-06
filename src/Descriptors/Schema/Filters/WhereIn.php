<?php

namespace LaravelJsonApi\OpenApiSpec\Descriptors\Schema\Filters;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Example;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use LaravelJsonApi\Eloquent\Filters\WhereNotIn;
use LaravelJsonApi\Eloquent\Filters\WherePivotNotIn;

class WhereIn extends FilterDescriptor
{
    /**
     * @todo Pay attention to delimiter
     */
    public function filter(): array
    {
        $key = $this->filter->key();
        $examples = collect($this->generator->resources()
          ->resources($this->route->schema()::model()))
          ->pluck($key)
          ->map(function ($f) {
              // @todo Watch out for ids?
              return Example::create($f)->value($f);
          })
          ->toArray();

        return [
          Parameter::query()
            ->name("filter[{$this->filter->key()}]")
            ->description($this->description())
            ->required(false)
            ->allowEmptyValue(false)
            ->schema(Schema::array()->items(Schema::string())->default(Example::create('empty')->value([])))
            ->examples(...$examples)
            ->style('form')
            ->explode(false),
        ];
    }

    protected function description(): string
    {
        return $this->filter instanceof WhereNotIn || $this->filter instanceof WherePivotNotIn
            ? "A list of {$this->filter->key()}s to exclude by."
            : "A list of {$this->filter->key()}s to filter by.";
    }
}
