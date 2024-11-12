<?php

namespace LaravelJsonApi\OpenApiSpec\Tests\Support\JsonApi\V1\Sites\Capabilities;

use Illuminate\Support\Str;
use LaravelJsonApi\NonEloquent\Capabilities\CrudResource;
use LaravelJsonApi\OpenApiSpec\Tests\Support\Entities\Site;
use LaravelJsonApi\OpenApiSpec\Tests\Support\Entities\SiteStorage;

class CrudSite extends CrudResource
{
    protected SiteStorage $sites;

    public function __construct(SiteStorage $sites)
    {
        parent::__construct();

        $this->sites = $sites;
    }

    public function create(array $validatedData): Site
    {
        $site = new Site($validatedData['slug']);
        $site->setDomain($validatedData['domain'] ?? null);
        $site->setName($validatedData['name'] ?? null);

        $this->sites->store($site);

        return $site;
    }

    public function read(Site $site): ?Site
    {
        $filters = $this->queryParameters->filter();

        if ($filters && $name = $filters->value('name')) {
            return Str::contains($site->getName(), $name) ? $site : null;
        }

        return $site;
    }

    public function update(Site $site, array $validatedData): Site
    {
        if (array_key_exists('domain', $validatedData)) {
            $site->setDomain($validatedData['domain']);
        }

        if (array_key_exists('name', $validatedData)) {
            $site->setName($validatedData['name']);
        }

        $this->sites->store($site);

        return $site;
    }

    public function delete(Site $site): void
    {
        $this->sites->remove($site);
    }
}
