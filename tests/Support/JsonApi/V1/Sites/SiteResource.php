<?php

namespace LaravelJsonApi\OpenApiSpec\Tests\Support\JsonApi\V1\Sites;

use LaravelJsonApi\Core\Resources\JsonApiResource;

class SiteResource extends JsonApiResource
{
    public function id(): string
    {
        return $this->resource->getSlug();
    }

    public function attributes($request): iterable
    {
        return [
            'domain' => $this->resource->getDomain(),
            'name' => $this->resource->getName(),
        ];
    }
}
