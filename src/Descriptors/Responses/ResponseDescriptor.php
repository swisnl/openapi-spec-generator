<?php

namespace LaravelJsonApi\OpenApiSpec\Descriptors\Responses;

use GoldSpecDigital\ObjectOrientedOAS\Contracts\SchemaContract;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Illuminate\Support\Collection;
use LaravelJsonApi\OpenApiSpec\Builders\Paths\Operation\ResponseBuilder;
use LaravelJsonApi\OpenApiSpec\Builders\Paths\Operation\SchemaBuilder;
use LaravelJsonApi\OpenApiSpec\ComponentsContainer;
use LaravelJsonApi\OpenApiSpec\Contracts\Descriptors\ResponseDescriptor as ResponseDescriptorContract;
use LaravelJsonApi\OpenApiSpec\Descriptors\Descriptor;
use LaravelJsonApi\OpenApiSpec\Generator;
use LaravelJsonApi\OpenApiSpec\Route;
use Neomerx\JsonApi\Contracts\Http\Headers\MediaTypeInterface;

abstract class ResponseDescriptor extends Descriptor implements ResponseDescriptorContract
{
    protected Route $route;

    protected ComponentsContainer $components;

    protected SchemaBuilder $schemaBuilder;

    protected Collection $defaults;

    protected bool $hasId = false;

    protected bool $validates = false;

    public function __construct(
        Generator $generator,
        Route $route,
        SchemaBuilder $schemaBuilder,
        Collection $defaults,
    ) {
        parent::__construct($generator);
        $this->route = $route;
        $this->components = $this->generator->components();
        $this->schemaBuilder = $schemaBuilder;
        $this->defaults = $defaults;
    }

    /**
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Response[]
     */
    abstract public function response(): array;

    /**
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     */
    protected function ok(): Response
    {
        return Response::ok()
            ->description($this->description())
            ->content(
                MediaType::create()
                    ->mediaType(MediaTypeInterface::JSON_API_MEDIA_TYPE)
                    ->schema(ResponseBuilder::buildResponse($this->data(),
                        $this->meta(), $this->links()))
            );
    }

    protected function noContent(): Response
    {
        return Response::create()->statusCode(204)->description('No Content');
    }

    protected function defaults(): array
    {
        $except = [];
        if (! $this->hasId) {
            $except[] = '404';
        }
        if (! $this->validates) {
            $except[] = '422';
        }

        return $this->defaults->except($except)->toArray();
    }

    protected function description(): string
    {
        return ucfirst($this->route->action()).' '.$this->route->name();
    }

    abstract protected function data(): SchemaContract;

    protected function meta(): ?Schema
    {
        return null;
    }

    protected function links(): ?Schema
    {
        return null;
    }
}
