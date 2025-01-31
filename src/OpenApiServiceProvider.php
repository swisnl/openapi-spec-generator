<?php

namespace LaravelJsonApi\OpenApiSpec;

use Illuminate\Support\ServiceProvider;

class OpenApiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('openapi.php'),
            ], 'config');

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/openapi'),
            ], 'lang');*/

            // Registering package commands.
            $this->commands([
                Commands\GenerateCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'openapi');

        // Register the main class to use with the facade
        $this->app->singleton('openapi-generator', function () {
            return new OpenApiGenerator;
        });
    }
}
