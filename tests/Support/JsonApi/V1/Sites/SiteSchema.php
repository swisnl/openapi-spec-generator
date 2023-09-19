<?php

namespace LaravelJsonApi\OpenApiSpec\Tests\Support\JsonApi\V1\Sites;

use LaravelJsonApi\Core\Schema\Schema;
use LaravelJsonApi\NonEloquent\Fields\Attribute;
use LaravelJsonApi\NonEloquent\Fields\ID;
use LaravelJsonApi\NonEloquent\Filters\Filter;
use LaravelJsonApi\OpenApiSpec\Tests\Support\Entities\Site;

class SiteSchema extends Schema
{
    public static string $model = Site::class;

    public function fields(): iterable
    {
        return [
            ID::make()->matchAs('.+'),
            Attribute::make('domain'),
            Attribute::make('name'),
        ];
    }

    public function filters(): iterable
    {
        return [
            Filter::make('name'),
            Filter::make('slugs'),
        ];
    }

    public function repository(): SiteRepository
    {
        return SiteRepository::make()
            ->withServer($this->server)
            ->withSchema($this);
    }
}
