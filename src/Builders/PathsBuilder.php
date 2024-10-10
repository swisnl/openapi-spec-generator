<?php

namespace LaravelJsonApi\OpenApiSpec\Builders;

use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;
use Illuminate\Routing\Route as IlluminateRoute;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use LaravelJsonApi\OpenApiSpec\Builders\Paths\OperationBuilder;
use LaravelJsonApi\OpenApiSpec\ComponentsContainer;
use LaravelJsonApi\OpenApiSpec\Generator;
use LaravelJsonApi\OpenApiSpec\Route as SpecRoute;

class PathsBuilder extends Builder
{
    protected ComponentsContainer $components;

    protected OperationBuilder $operation;

    public function __construct(
        Generator $generator,
        ComponentsContainer $components,
    ) {
        parent::__construct($generator);
        $this->components = $components;
        $this->operation = new OperationBuilder($generator, $components);
    }

    /**
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem[]
     */
    public function build(): array
    {
        return collect(Route::getRoutes()->getRoutes())
          ->filter(
              fn (IlluminateRoute $route) => SpecRoute::belongsTo($route,
                  $this->generator->server())
          )
          ->map(fn (IlluminateRoute $route) => new SpecRoute($this->generator->server(), $route))
          ->mapToGroups(function (SpecRoute $route) {
              return [$route->uri() => $route];
          })
          ->map(function (Collection $routes, string $uri) {
              $operations = $routes
                ->map(function (SpecRoute $route) {
                    return $this->operation->build($route);
                })
                ->filter(fn ($val) => $val !== null);

              if ($operations->isEmpty()) {
                  return null;
              }

              return PathItem::create()
                ->route($uri)
                ->operations(...$operations->toArray());
          })
          ->filter(fn ($val) => $val !== null)
          ->toArray();
    }
}
