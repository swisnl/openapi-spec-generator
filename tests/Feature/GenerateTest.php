<?php

namespace LaravelJsonApi\OpenApiSpec\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use LaravelJsonApi\OpenApiSpec\Facades\GeneratorFacade;
use LaravelJsonApi\OpenApiSpec\Tests\Support\Database\Seeders\DatabaseSeeder;
use LaravelJsonApi\OpenApiSpec\Tests\TestCase;
use Symfony\Component\Yaml\Yaml;

class GenerateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
    }

    public function test_spec_is_yaml()
    {
        $openapiYaml = GeneratorFacade::generate('v1', 'yaml');

        $spec = Yaml::parse($openapiYaml);

        $this->assertEquals('My JSON:API', $spec['info']['title']);
    }

    public function test_spec_is_json()
    {
        $output = GeneratorFacade::generate('v1', 'json');

        $spec = json_decode($output, true);

        $this->assertEquals('My JSON:API', $spec['info']['title']);
    }

    public function test_spec_file_generated()
    {
        GeneratorFacade::generate('v1');

        $openapiYaml = Storage::disk(config('openapi.filesystem_disk'))->get('v1_openapi.yaml');

        $spec = Yaml::parse($openapiYaml);

        $this->assertEquals('My JSON:API', $spec['info']['title']);
    }

    public function test_url_is_properly_parsed()
    {
        GeneratorFacade::generate('v1');

        $openapiYaml = GeneratorFacade::generate('v1');

        $spec = Yaml::parse($openapiYaml);

        $this->assertArrayHasKey('/posts', $spec['paths'], 'Path to resource is not replaced correctly.');

        $this->assertArrayHasKey('/posts/{post}/relationships/author', $spec['paths'], 'Path to resource is not replaced correctly.');

        $this->assertEquals('http://localhost/api/v1', $spec['servers'][0]['variables']['serverUrl']['default']);
    }
}
