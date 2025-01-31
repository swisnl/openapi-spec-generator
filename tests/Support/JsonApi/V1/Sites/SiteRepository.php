<?php

namespace LaravelJsonApi\OpenApiSpec\Tests\Support\JsonApi\V1\Sites;

use LaravelJsonApi\Contracts\Store\CreatesResources;
use LaravelJsonApi\Contracts\Store\DeletesResources;
use LaravelJsonApi\Contracts\Store\QueriesAll;
use LaravelJsonApi\Contracts\Store\QueryManyBuilder;
use LaravelJsonApi\Contracts\Store\UpdatesResources;
use LaravelJsonApi\NonEloquent\AbstractRepository;
use LaravelJsonApi\NonEloquent\Concerns\HasCrudCapability;
use LaravelJsonApi\OpenApiSpec\Tests\Support\Entities\SiteStorage;

class SiteRepository extends AbstractRepository implements CreatesResources, DeletesResources, QueriesAll, UpdatesResources
{
    use HasCrudCapability;

    protected SiteStorage $siteStorage;

    public function __construct(SiteStorage $siteStorage)
    {
        $this->siteStorage = $siteStorage;
    }

    public function find(string $resourceId): ?object
    {
        return $this->siteStorage->find($resourceId);
    }

    public function queryAll(): QueryManyBuilder
    {
        return Capabilities\QuerySites::make()
            ->withServer($this->server())
            ->withSchema($this->schema());
    }

    protected function crud(): Capabilities\CrudSite
    {
        return Capabilities\CrudSite::make();
    }
}
