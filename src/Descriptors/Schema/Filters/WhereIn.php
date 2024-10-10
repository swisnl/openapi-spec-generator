<?php

namespace LaravelJsonApi\OpenApiSpec\Descriptors\Schema\Filters;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Example;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use LaravelJsonApi\Contracts\Schema\Filter;
use LaravelJsonApi\Eloquent\Filters\WhereNotIn;
use LaravelJsonApi\Eloquent\Filters\WherePivotNotIn;

class WhereIn extends FilterDescriptor
{
    /**
     * @var \LaravelJsonApi\Eloquent\Filters\WhereIn
     */
    protected Filter $filter;

    /**
     * @todo Pay attention to delimiter
     */
    public function filter(): array
    {
        $examples = collect($this->generator->resources()
          ->resources($this->route->schema()::model()))
          ->pluck($this->filter->column())
          ->filter()
          ->map(function ($f) {
              if (function_exists('enum_exists') && $f instanceof \UnitEnum) {
                  $f = $f instanceof \BackedEnum ? $f->value : $f->name;
              }

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
