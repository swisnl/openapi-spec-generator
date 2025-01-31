<?php

namespace LaravelJsonApi\OpenApiSpec\Descriptors\Requests;

use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use LaravelJsonApi\OpenApiSpec\Builders\Paths\Operation\SchemaBuilder;
use LaravelJsonApi\OpenApiSpec\ComponentsContainer;
use LaravelJsonApi\OpenApiSpec\Contracts\Descriptors\RequestDescriptor as RequestDescriptorContract;
use LaravelJsonApi\OpenApiSpec\Descriptors\Descriptor;
use LaravelJsonApi\OpenApiSpec\Generator;
use LaravelJsonApi\OpenApiSpec\Route;
use Neomerx\JsonApi\Contracts\Http\Headers\MediaTypeInterface;

/**
 * @todo Resolve to the request class
 * @todo Overlay resource schema with validation rules from the request class
 */
abstract class RequestDescriptor extends Descriptor implements RequestDescriptorContract
{
    protected Route $route;

    protected ComponentsContainer $components;

    protected SchemaBuilder $schemaBuilder;

    public function __construct(
        Generator $generator,
        Route $route,
        SchemaBuilder $schemaBuilder,
    ) {
        parent::__construct($generator);
        $this->route = $route;
        $this->components = $this->generator->components();
        $this->schemaBuilder = $schemaBuilder;
    }

    public function request(): RequestBody
    {
        return RequestBody::create()
            ->content(
                MediaType::create()
                    ->mediaType(MediaTypeInterface::JSON_API_MEDIA_TYPE)
                    ->schema(
                        Schema::object()->properties(
                            $this->schemaBuilder->build($this->route, true)
                                ->objectId('data')
                        )
                            ->required('data')
                    )
            );
    }
}
