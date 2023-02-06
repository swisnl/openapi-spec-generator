<?php

namespace LaravelJsonApi\OpenApiSpec;

use GoldSpecDigital\ObjectOrientedOAS\OpenApi;
use LaravelJsonApi\Contracts\Server\Server;
use LaravelJsonApi\Core\Support\AppResolver;
use LaravelJsonApi\OpenApiSpec\Builders\InfoBuilder;
use LaravelJsonApi\OpenApiSpec\Builders\PathsBuilder;
use LaravelJsonApi\OpenApiSpec\Builders\ServerBuilder;

class Generator
{
    protected string $key;

    protected Server $server;

    protected InfoBuilder $infoBuilder;

    protected ServerBuilder $serverBuilder;

    protected PathsBuilder $pathsBuilder;

    protected ComponentsContainer $components;

    protected ResourceContainer $resources;

    /**
     * Generator constructor.
     *
     * @param $key
     */
    public function __construct($key)
    {
        $this->key = $key;

        $apiServer = config("jsonapi.servers.$key");
        $appResolver = app(AppResolver::class);

        $this->server = new $apiServer($appResolver, $this->key);

        $this->infoBuilder = new InfoBuilder($this);
        $this->serverBuilder = new ServerBuilder($this);
        $this->components = new ComponentsContainer();
        $this->resources = new ResourceContainer($this->server);
        $this->pathsBuilder = new PathsBuilder($this, $this->components);
    }

    /**
     * @return \GoldSpecDigital\ObjectOrientedOAS\OpenApi
     */
    public function generate(): OpenApi
    {
        return OpenApi::create()
          ->openapi(OpenApi::OPENAPI_3_0_2)
          ->info($this->infoBuilder->build())
          ->servers(...$this->serverBuilder->build())
          ->paths(...array_values($this->pathsBuilder->build()))
          ->components($this->components()->components());
    }

    /**
     * @return string
     */
    public function key(): string
    {
        return $this->key;
    }

    public function server(): Server
    {
        return $this->server;
    }

    /**
     * @return \LaravelJsonApi\OpenApiSpec\ComponentsContainer
     */
    public function components(): ComponentsContainer
    {
        return $this->components;
    }

    /**
     * @return \LaravelJsonApi\OpenApiSpec\ResourceContainer
     */
    public function resources(): ResourceContainer
    {
        return $this->resources;
    }
}
