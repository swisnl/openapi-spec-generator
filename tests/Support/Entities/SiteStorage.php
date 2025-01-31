<?php

declare(strict_types=1);

namespace LaravelJsonApi\OpenApiSpec\Tests\Support\Entities;

use Illuminate\Filesystem\Filesystem;

class SiteStorage
{
    protected Filesystem $files;

    /**
     * @var array<int, array<string, mixed>
     */
    protected array $sites;

    public function __construct(Filesystem $files)
    {
        $this->files = $files;
        $this->sites = json_decode($files->get('sites.json'), true);
    }

    public function find(string $slug): ?Site
    {
        if (! isset($this->sites[$slug])) {
            return null;
        }

        return Site::fromArray($slug, $this->sites[$slug]);
    }

    public function cursor(): \Generator
    {
        foreach ($this->sites as $slug => $values) {
            yield $slug => Site::fromArray($slug, $values);
        }
    }

    public function all(): array
    {
        return iterator_to_array($this->cursor());
    }

    public function store(Site $site): void
    {
        $this->sites[$site->getSlug()] = $site->toArray();

        $this->write();
    }

    public function remove(Site $site): void
    {
        unset($this->sites[$site->getSlug()]);

        $this->write();
    }

    public function write(): void
    {
        $this->files->put('sites.json', json_encode($this->sites));
    }
}
