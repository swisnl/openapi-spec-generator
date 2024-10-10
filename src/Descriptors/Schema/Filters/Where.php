<?php

namespace LaravelJsonApi\OpenApiSpec\Descriptors\Schema\Filters;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Example;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema as OASchema;
use LaravelJsonApi\Contracts\Schema\Filter;

class Where extends FilterDescriptor
{
    /**
     * @var \LaravelJsonApi\Eloquent\Filters\Where
     */
    protected Filter $filter;

    /**
     * @todo Pay attention to isSingular
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
              ->schema(OASchema::string()->default(''))
              ->examples(...$examples),
        ];
    }

    protected function description(): string
    {
        return 'Filters the records';
    }
}
