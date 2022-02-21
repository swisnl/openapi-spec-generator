<?php

namespace LaravelJsonApi\OpenApiSpec\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use LaravelJsonApi\OpenApiSpec\Facades\GeneratorFacade;
use LaravelJsonApi\OpenApiSpec\Tests\Support\Database\Seeders\DatabaseSeeder;
use LaravelJsonApi\OpenApiSpec\Tests\TestCase;

class OpenApiSchemaTest extends TestCase
{
    use RefreshDatabase;

    private array $spec;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);

        $output = GeneratorFacade::generate('v1', 'json');
        $this->spec = json_decode($output, true);
    }

    public function test_has_many_should_have_array_as_type(): void
    {
        $this->assertEquals('array', $this->spec['components']['schemas']['resources.posts.relationship.tags.update']['type']);
        $this->assertEquals('array', $this->spec['components']['schemas']['resources.posts.relationship.tags.attach']['type']);
        $this->assertEquals('array', $this->spec['components']['schemas']['resources.posts.relationship.tags.detach']['type']);
    }
}
