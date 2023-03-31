<?php

namespace LaravelJsonApi\OpenApiSpec\Builders\Paths\Operation;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Example;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema as OASchema;
use LaravelJsonApi\OpenApiSpec\Builders\Builder;
use LaravelJsonApi\OpenApiSpec\Descriptors\Schema\Schema;
use LaravelJsonApi\OpenApiSpec\Route;

class ParameterBuilder extends Builder
{
    /**
     * @param \LaravelJsonApi\OpenApiSpec\Route $route
     *
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter[]
     */
    public function build(Route $route): array
    {
        // @todo Build a schema resolver with reusable instances
        $schemaDescriptor = new Schema($this->generator);
        $parameters = [];

        /**
         * Add pagination, filters & sorts
         */
        if ($route->action() === 'index') {
            $parameters = [
                ...$parameters,
                ...$schemaDescriptor->pagination($route),
                ...$schemaDescriptor->sortables($route),
                ...$schemaDescriptor->filters($route),
            ];
        }

        /**
         * Add id path parameter
         */
        if (isset($route->route()->defaults[\LaravelJsonApi\Laravel\Routing\Route::RESOURCE_ID_NAME])) {
            $id = $route->route()->defaults[\LaravelJsonApi\Laravel\Routing\Route::RESOURCE_ID_NAME];
            $examples = collect($this->generator->resources()
                ->resources($route->schema()::model()))
                ->map(function ($resource) {
                    $id = $resource->id();
                    return Example::create($id)->value($id);
                })->toArray();

            $parameters[] = Parameter::path($id)
                ->name($id)
                ->required(true)
                ->allowEmptyValue(false)
                ->examples(...$examples)
                ->schema(OASchema::string());
        }

        /**
         * Add any custom parameters
         */
        if ($route->schema() instanceof \LaravelJsonApi\OpenApiSpec\Contracts\ExtendsParameters) {
            $parameters = [
                ...$parameters,
                ...$route->schema()->customParameters($route->route()->getName()),
            ];
        }

        return $parameters;
    }
}
