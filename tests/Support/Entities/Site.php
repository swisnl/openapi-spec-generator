<?php

declare(strict_types=1);

namespace LaravelJsonApi\OpenApiSpec\Tests\Support\Entities;

use Illuminate\Contracts\Support\Arrayable;

class Site implements Arrayable
{
    private string $slug;

    private ?string $domain;

    private ?string $name;

    public static function fromArray(string $slug, array $values): self
    {
        $site = new self($slug);
        $site->setDomain($values['domain'] ?? null);
        $site->setName($values['name'] ?? null);

        return $site;
    }

    public function __construct(string $slug)
    {
        $this->slug = $slug;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function setDomain(?string $domain): Site
    {
        $this->domain = $domain;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): Site
    {
        $this->name = $name;

        return $this;
    }

    public function toArray(): array
    {
        return [
            $this->getDomain(),
            $this->getName(),
        ];
    }
}
