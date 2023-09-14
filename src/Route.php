<?php

namespace LaravelJsonApi\OpenApiSpec;

use Illuminate\Routing\Route as IlluminateRoute;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use LaravelJsonApi\Contracts\Schema\PolymorphicRelation;
use LaravelJsonApi\Contracts\Schema\Schema;
use LaravelJsonApi\Contracts\Server\Server;
use LaravelJsonApi\Eloquent\Fields\Relations\Relation;

class Route
{
    protected Server $server;

    protected Schema $schema;

    protected IlluminateRoute $route;

    protected string $resource;

    /**
     * @var string The controller class FQN
     */
    protected string $controller;

    /**
     * @var string The method name on the controller
     */
    protected string $method;

    /**
     * The last part of the route name. For the 'showRelated' method, the name
     * is manually added.
     *
     * @var string
     */
    protected string $action;

    /**
     * @var string The route name without the prefix
     */
    protected string $operationId;

    /**
     * @var string
     */
    protected string $uri;

    /**
     * @var string|null
     */
    protected ?string $relation = null;

    /**
     * Route constructor.
     *
     * @param \LaravelJsonApi\Contracts\Server\Server $server
     * @param \Illuminate\Routing\Route               $route
     */
    public function __construct(Server $server, IlluminateRoute $route)
    {
        $this->server = $server;
        $this->route = $route;

        $segments = explode('.', $this->route->getName());
        $segments = array_slice(
            $segments,
            array_search($this->server->name(), $segments) + 1
        );

        $this->operationId = collect($segments)->join('.');
        $relation = null;

        if (count($segments) === 2) {
            [$resource, $action] = $segments;
        } elseif (count($segments) === 3) {
            [$resource, $relation, $action] = $segments;
        } else {
            throw new \LogicException('Unable to handle action structure '.$route->getName());
        }

        $this->resource = $resource;
        $this->schema = $this->server->schemas()->schemaFor($resource);

        if ($action !== null && $relation === null && $this->schema->isRelationship($action)) {
            $this->relation = $action;
            $this->action = 'showRelated';
        } else {
            $this->relation = $relation;
            $this->action = $action;
        }

        $this->setUriForRoute();

        [$controller, $method] = explode('@', $this->route->getActionName(), 2);

        $this->controller = $controller;
        $this->method = $method;
    }

    /**
     * @return string The HTTP method
     */
    public function method(): string
    {
        return collect($this->route->methods())
          ->filter(fn ($method) => $method !== 'HEAD')
          ->first();
    }

    /**
     * @return \LaravelJsonApi\Contracts\Schema\Schema
     */
    public function schema(): Schema
    {
        return $this->schema;
    }

    /**
     * @return \Illuminate\Routing\Route
     */
    public function route(): IlluminateRoute
    {
        return $this->route;
    }

    /**
     * @return string[]
     */
    public function controllerCallable(): array
    {
        return [$this->controller, $this->method];
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->operationId;
    }

    /**
     * @return string
     */
    public function uri(): string
    {
        return $this->uri;
    }

    /**
     * @return string|null
     */
    public function relationName(): ?string
    {
        return $this->relation;
    }

    /**
     * @return \LaravelJsonApi\Eloquent\Fields\Relations\Relation|null
     */
    public function relation(): ?Relation
    {
        $relation = $this->relation ? $this->schema()
          ->relationship($this->relation) : null;

        if ($relation !== null && !($relation instanceof Relation)) {
            throw new \RuntimeException('Unexpected Type');
        }

        return $relation;
    }

    /**
     * @return bool
     */
    public function isRelation(): bool
    {
        return $this->relation !== null;
    }

    /**
     * @return bool
     */
    public function isPolymorphic(): bool
    {
        return $this->relation() instanceof PolymorphicRelation;
    }

    /**
     * @return string|null
     */
    public function invers(): ?string
    {
        return $this->relation() !== null ? $this->relation()->inverse() : null;
    }

    /**
     * @return \LaravelJsonApi\Contracts\Schema\Schema|null
     */
    public function inversSchema(): ?Schema
    {
        if ($this->isRelation()) {
            if ($this->relation() instanceof PolymorphicRelation) {
                throw new \LogicException('Method is not allowed for Polymorphic relationships');
            }

            return $this->server->schemas()
              ->schemaFor($this->relation() !== null ? $this->relation()->inverse() : null);
        }

        return null;
    }

    /**
     * @return \LaravelJsonApi\Contracts\Schema\Schema[]
     */
    public function inversSchemas(): array
    {
        $schemas = [];
        if ($this->isRelation()) {
            $relation = $this->relation();
            if ($relation instanceof PolymorphicRelation) {
                foreach ($relation->inverseTypes() as $type) {
                    $schemas[$type] = $this->server->schemas()
                      ->schemaFor($type);
                }
            } else {
                $schemas[$relation->inverse()] = $this->server->schemas()
                  ->schemaFor($relation->inverse());
            }
        }

        return $schemas;
    }

    /**
     * @param bool $singular
     *
     * @return string|null
     */
    public function inverseName(bool $singular = false): ?string
    {
        $relation = $this->relation() !== null ? $this->relation()->inverse() : null;
        if ($singular) {
            return Str::singular($relation);
        }

        return $relation;
    }

    /**
     * @param false $singular
     *
     * @return string
     */
    public function name(bool $singular = false): string
    {
        if ($singular) {
            return Str::singular($this->resource);
        }

        return $this->resource;
    }

    public function resource(): string
    {
        return $this->resource;
    }

    /**
     * @return string
     */
    public function action(): string
    {
        return $this->action;
    }

    public static function belongsTo(
        IlluminateRoute $route,
        Server $server
    ): bool {
        return Str::contains(
            $route->getName(),
            $server->name(),
        );
    }

    protected function setUriForRoute(): void
    {
        $domain = URL::to('/');
        $serverBasePath = str_replace(
            $domain,
            '',
            $this->server->url(),
        );

        $this->uri = str_replace(
            $serverBasePath,
            '',
            '/'.$this->route->uri(),
        );
    }
}
