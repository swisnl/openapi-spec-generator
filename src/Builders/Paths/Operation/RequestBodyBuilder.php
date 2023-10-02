<?php

namespace LaravelJsonApi\OpenApiSpec\Builders\Paths\Operation;

use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use LaravelJsonApi\Laravel\Http\Controllers;
use LaravelJsonApi\OpenApiSpec\Builders\Builder;
use LaravelJsonApi\OpenApiSpec\Concerns\ResolvesActionTraitToDescriptor;
use LaravelJsonApi\OpenApiSpec\Contracts\Descriptors\RequestDescriptor;
use LaravelJsonApi\OpenApiSpec\Descriptors;
use LaravelJsonApi\OpenApiSpec\Generator;
use LaravelJsonApi\OpenApiSpec\Route;

class RequestBodyBuilder extends Builder
{
    use ResolvesActionTraitToDescriptor;

    protected SchemaBuilder $schemaBuilder;

    protected array $descriptors = [
      Controllers\Actions\Store::class => Descriptors\Requests\Store::class,
      Controllers\Actions\Update::class => Descriptors\Requests\Update::class,
      Controllers\Actions\AttachRelationship::class => Descriptors\Requests\AttachRelationship::class,
      Controllers\Actions\DetachRelationship::class => Descriptors\Requests\DetachRelationship::class,
      Controllers\Actions\UpdateRelationship::class => Descriptors\Requests\UpdateRelationship::class,
    ];

    public function __construct(
        Generator $generator,
        SchemaBuilder $schemaBuilder
    ) {
        parent::__construct($generator);
        $this->schemaBuilder = $schemaBuilder;
    }

    public function build(Route $route): ?RequestBody
    {
        return $this->getDescriptor($route) !== null ? $this->getDescriptor($route)->request() : null;
    }

    /**
     * @param \LaravelJsonApi\OpenApiSpec\Route $route
     *
     * @return \LaravelJsonApi\OpenApiSpec\Descriptors\Actions\ActionDescriptor|null
     */
    protected function getDescriptor(Route $route): ?RequestDescriptor
    {
        $class = $this->descriptorClass($route);
        if (isset($this->descriptors[$class])) {
            return new $this->descriptors[$class](
                $this->generator,
                $route,
                $this->schemaBuilder
            );
        }

        return null;
    }
}
