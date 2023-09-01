<?php

namespace LaravelJsonApi\OpenApiSpec\Builders\Paths;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use LaravelJsonApi\Laravel\Http\Controllers;
use LaravelJsonApi\OpenApiSpec\Builders\Builder;
use LaravelJsonApi\OpenApiSpec\Builders\Paths\Operation\ParameterBuilder;
use LaravelJsonApi\OpenApiSpec\Builders\Paths\Operation\RequestBodyBuilder;
use LaravelJsonApi\OpenApiSpec\Builders\Paths\Operation\ResponseBuilder;
use LaravelJsonApi\OpenApiSpec\Builders\Paths\Operation\SchemaBuilder;
use LaravelJsonApi\OpenApiSpec\ComponentsContainer;
use LaravelJsonApi\OpenApiSpec\Concerns\ResolvesActionTraitToDescriptor;
use LaravelJsonApi\OpenApiSpec\Descriptors;
use LaravelJsonApi\OpenApiSpec\Generator;
use LaravelJsonApi\OpenApiSpec\Route;
use LaravelJsonApi\OpenApiSpec\Route as SpecRoute;

class OperationBuilder extends Builder
{
    use ResolvesActionTraitToDescriptor;

    protected ComponentsContainer $components;

    protected ParameterBuilder $parameterBuilder;

    protected RequestBodyBuilder $requestBodyBuilder;

    protected ResponseBuilder $responseBuilder;

    protected SchemaBuilder $schemaBuilder;

    protected array $descriptors = [
      Controllers\Actions\FetchMany::class => Descriptors\Actions\FetchMany::class,
      Controllers\Actions\FetchOne::class => Descriptors\Actions\FetchOne::class,
      Controllers\Actions\Store::class => Descriptors\Actions\Store::class,
      Controllers\Actions\Update::class => Descriptors\Actions\Update::class,
      Controllers\Actions\Destroy::class => Descriptors\Actions\Destroy::class,
      Controllers\Actions\FetchRelated::class => Descriptors\Actions\Relationship\FetchRelated::class,
      Controllers\Actions\AttachRelationship::class => Descriptors\Actions\Relationship\Attach::class,
      Controllers\Actions\DetachRelationship::class => Descriptors\Actions\Relationship\Detach::class,
      Controllers\Actions\FetchRelationship::class => Descriptors\Actions\Relationship\Fetch::class,
      Controllers\Actions\UpdateRelationship::class => Descriptors\Actions\Relationship\Update::class,
    ];

    public function __construct(
        Generator $generator,
        ComponentsContainer $components
    ) {
        parent::__construct($generator);
        $this->components = $components;

        $this->schemaBuilder = new SchemaBuilder($generator, $components);
        $this->parameterBuilder = new ParameterBuilder($generator);
        $this->requestBodyBuilder = new RequestBodyBuilder($generator,
            $this->schemaBuilder);
        $this->responseBuilder = new ResponseBuilder($generator, $components,
            $this->schemaBuilder);
    }

    /**
     * @param \LaravelJsonApi\OpenApiSpec\Route $route
     *
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Operation|null
     */
    public function build(SpecRoute $route): ?Operation
    {
        return $this->getDescriptor($route) !== null ? $this->getDescriptor($route)->action() : null;
    }

    /**
     * @param \LaravelJsonApi\OpenApiSpec\Route $route
     *
     * @return \LaravelJsonApi\OpenApiSpec\Descriptors\Actions\ActionDescriptor|null
     */
    protected function getDescriptor(Route $route): ?Descriptors\Actions\ActionDescriptor
    {
        $class = $this->descriptorClass($route);
        if (isset($this->descriptors[$class])) {
            return new $this->descriptors[$class](
                $this->parameterBuilder,
                $this->requestBodyBuilder,
                $this->responseBuilder,
                $this->generator,
                $route
            );
        }

        return null;
    }
}
