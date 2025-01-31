<?php

namespace LaravelJsonApi\OpenApiSpec\Descriptors\Schema\Filters;

use LaravelJsonApi\Contracts\Schema\Filter;
use LaravelJsonApi\OpenApiSpec\Contracts\Descriptors\FilterDescriptor as FilterDescriptorContract;
use LaravelJsonApi\OpenApiSpec\Descriptors\Descriptor;
use LaravelJsonApi\OpenApiSpec\Generator;
use LaravelJsonApi\OpenApiSpec\Route;

abstract class FilterDescriptor extends Descriptor implements FilterDescriptorContract
{
    protected Route $route;

    protected Filter $filter;

    public function __construct(Generator $generator, Route $route, Filter $filter)
    {
        parent::__construct($generator);

        $this->route = $route;
        $this->filter = $filter;
    }

    abstract protected function description(): string;
}
