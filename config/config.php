<?php

/*
 * OpenAPI Generator configuration
 */
return [
    'servers' => [
        'v1' => [
            /*
             * Info section: title and version are required, everything else can be commented out if not needed.
             * See https://swagger.io/specification/#info-object for more information.
             */
            'info' => [
                'title' => 'My JSON:API',
                'description' => 'JSON:API built using Laravel',
                'version' => '1.0.0',
                'termsOfService' => 'https://example.com/terms-of-service',
                'license' => [
                    'name' => 'MIT',
                    'url' => 'https://opensource.org/licenses/MIT',
                ],
                'contact' => [
                    'name' => 'API Support',
                    'url' => 'https://www.example.com/support',
                    'email' => 'support@example.com',
                ],
            ],
        ],
    ],

    /*
     * The storage disk to be used to place the generated `*_openapi.json` or `*_openapi.yaml` file.
     *
     * For example, if you use 'public' you can access the generated file as public web asset (after run `php artisan storage:link`).
     *
     * Supported: 'local', 'public' and (probably) any disk available in your filesystems (https://laravel.com/docs/9.x/filesystem#configuration).
     * Set it to `null` to use your default disk.
     */
    'filesystem_disk' => env('OPEN_API_SPEC_GENERATOR_FILESYSTEM_DISK', null),
];
