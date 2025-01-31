<?php

namespace LaravelJsonApi\OpenApiSpec\Tests;

use Illuminate\Support\Facades\App;
use LaravelJsonApi\Laravel\Routing\Registrar;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;
use LaravelJsonApi\OpenApiSpec\OpenApiServiceProvider;
use LaravelJsonApi\OpenApiSpec\Tests\Support\JsonApi\V1\Server;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Vinkla\Hashids\HashidsServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function defineEnvironment($app)
    {
        $app['config']->set('jsonapi.servers', [
            'v1' => Server::class,
        ]);

        $app['config']->set('hashids', [
            'default' => 'main',
            'connections' => [
                'main' => [
                    'salt' => 'Z3wxm8m6fxPMRtjX',
                    'length' => 10,
                ],
            ],
        ]);
    }

    protected function defineRoutes($router)
    {
        $router->group(['prefix' => 'api', 'middleware' => 'api', 'namespace' => 'LaravelJsonApi\OpenApiSpec\Tests\Support\Controllers'], function () {
            /** @var Registrar $jsonApiRoute */
            $jsonApiRoute = App::make(Registrar::class);

            $jsonApiRoute->server('v1')
                ->prefix('v1')
                ->namespace('Api\V1')
                ->resources(function (ResourceRegistrar $server) {
                    /* Posts */
                    $server->resource('posts')->relationships(function ($relationships) {
                        $relationships->hasOne('author')->readOnly();
                        $relationships->hasMany('comments')->readOnly();
                        $relationships->hasMany('media');
                        $relationships->hasMany('tags');
                    })->actions('-actions', function ($actions) {
                        $actions->delete('purge');
                        $actions->withId()->post('publish');
                    });

                    /* Videos */
                    $server->resource('videos')->relationships(function ($relationships) {
                        $relationships->hasMany('tags');
                    });

                    $server->resource('sites');
                });
        });
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/Support/Database/Migrations');
    }

    protected function getPackageProviders($app)
    {
        return [
            \LaravelJsonApi\Encoder\Neomerx\ServiceProvider::class,
            \LaravelJsonApi\Laravel\ServiceProvider::class,
            OpenApiServiceProvider::class,
            HashidsServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'OpenApiGenerator' => \LaravelJsonApi\OpenApiSpec\Facades\GeneratorFacade::class,
            'JsonApi' => \LaravelJsonApi\Core\Facades\JsonApi::class,
            'JsonApiRoute' => \LaravelJsonApi\Laravel\Facades\JsonApiRoute::class,
        ];
    }
}
