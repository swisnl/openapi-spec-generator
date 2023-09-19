<?php

namespace LaravelJsonApi\OpenApiSpec\Tests\Support\JsonApi\V1\Sites\Capabilities;

use LaravelJsonApi\NonEloquent\Capabilities\QueryAll;
use LaravelJsonApi\OpenApiSpec\Tests\Support\Entities\Site;
use LaravelJsonApi\OpenApiSpec\Tests\Support\Entities\SiteStorage;

class QuerySites extends QueryAll
{
    protected SiteStorage $sites;

    public function __construct(SiteStorage $sites)
    {
        parent::__construct();

        $this->sites = $sites;
    }

    public function get(): iterable
    {
        $sites = collect($this->sites->all());
        $filters = $this->queryParameters->filter();

        if ($filters && is_array($slugs = $filters->value('slugs'))) {
            $sites = $sites->filter(
                fn (Site $site) => in_array($site->getSlug(), $slugs)
            );
        }

        return $sites;
    }
}
