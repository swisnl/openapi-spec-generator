<?php

namespace LaravelJsonApi\OpenApiSpec;

use GoldSpecDigital\ObjectOrientedOAS\Contracts\SchemaContract;
use GoldSpecDigital\ObjectOrientedOAS\Objects\BaseObject;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Components;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\SecurityScheme;

class ComponentsContainer
{
    protected array $schemas = [];

    protected array $requestBodies = [];

    protected array $parameters = [];

    protected array $responses = [];

    /**
     * @param \GoldSpecDigital\ObjectOrientedOAS\Objects\Schema $schema
     *
     * @return Schema
     */
    public function addSchema(SchemaContract $schema): SchemaContract
    {
        $this->schemas[$schema->objectId] = $schema;

        return $this->ref($schema);
    }

    /**
     * @param string $objectId
     *
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Schema|null
     */
    public function getSchema(string $objectId): ?Schema
    {
        return isset($this->schemas[$objectId]) ? $this->ref($this->schemas[$objectId]) : null;
    }

    public function addRequestBody(RequestBody $requestBody): BaseObject
    {
        $this->requestBodies[$requestBody->objectId] = $requestBody;

        return $this->ref($requestBody);
    }

    public function getRequestBody(string $objectId): ?BaseObject
    {
        return $this->requestBodies[$objectId] ?? null;
    }

    public function addParameter(Parameter $parameter): BaseObject
    {
        $this->parameters[$parameter->objectId] = $parameter;

        return $this->ref($parameter);
    }

    public function getParameter(string $objectId): ?BaseObject
    {
        return $this->parameters[$objectId] ?? null;
    }

    public function addResponse(Response $response): BaseObject
    {
        $this->responses[$response->objectId] = $response;

        return Response::ref('#/components/responses/'.$response->objectId,
            $response->objectId)->statusCode($response->statusCode);
    }

    public function getResponse(string $objectId): ?BaseObject
    {
        return $this->responses[$objectId] ?? null;
    }

    /**
     * @return Components
     */
    public function components(): Components
    {
        $schemas = collect($this->schemas)
          ->sortBy(fn (BaseObject $schema) => $schema->objectId)
          ->toArray();

        return Components::create()
          ->responses(...$this->responses)
          ->parameters(...$this->parameters)
          ->requestBodies(...$this->requestBodies)
          ->schemas(...array_values($schemas));
    }

    /**
     * @param \GoldSpecDigital\ObjectOrientedOAS\Objects\BaseObject $object
     *
     * @return mixed
     */
    protected function ref(BaseObject $object): BaseObject
    {
        switch (true) {
            case $object instanceof Parameter:
                $baseRef = '#/components/parameters/';
                break;
            case $object instanceof RequestBody:
                $baseRef = '#/components/requestBodies/';
                break;
            case $object instanceof Response:
                $baseRef = '#/components/responses/';
                break;
            case $object instanceof SchemaContract:
                $baseRef = '#/components/schemas/';
                break;
            case $object instanceof SecurityScheme:
                $baseRef = '#/components/securitySchemes/';
                break;
            default:
                exit(get_class($object));
        }

        return $object::ref($baseRef.$object->objectId,
            $object->objectId);
    }
}
