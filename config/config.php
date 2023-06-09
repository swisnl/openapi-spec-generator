<?php

/*
 * OpenAPI Generator configuration
 */
return [
    'servers' => [
        'v1' => [
            'info' => [
                'title' => 'My JSON:API',
                'description' => 'JSON:API built using Laravel',
                'version' => '1.0.0',
            ],

            /*
             * Available security schemes, each scheme needs a unique name as key of the entry
             * This unique name can be used in the security array below to enable the scheme at the root level
             * Examples commented below
             */
            'securitySchemes' => [
//                'Bearer' => [
//                    'type'         => 'http',
//                    'description'  => 'My http Scheme description',
//                    'scheme'       => 'bearer',
//                    'bearerFormat' => 'JWT',
//                ],
//                'ApiKey' => [
//                    'type'        => 'apiKey',
//                    'description' => 'My apiKey Scheme description',
//                    'name'        => 'X-API-KEY',
//                    'in'          => 'header',
//                ],
//                'OAuth2' => [
//                    'type'        => 'oauth2',
//                    'description' => 'My oauth2 Scheme description',
//                    'flows'       => [
//                        'implicit'          => [
//                            'authorizationUrl' => 'https://example.com/api/oauth/dialog',
//                            'scopes'           => [
//                                'write:posts' => 'modify posts in your account',
//                                'read:posts'  => 'read your posts',
//                            ],
//                        ],
//                        'password'          => [
//                            'tokenUrl' => 'https://example.com/api/oauth/token',
//                            'scopes'   => [
//                                'write:posts' => 'modify posts in your account',
//                                'read:posts'  => 'read your posts',
//                            ],
//                        ],
//                        'clientCredentials' => [
//                            'tokenUrl' => 'https://example.com/api/oauth/token',
//                            'scopes'   => [
//                                'write:posts' => 'modify posts in your account',
//                                'read:posts'  => 'read your posts',
//                            ],
//                        ],
//                        'authorizationCode' => [
//                            'authorizationUrl' => 'https://example.com/api/oauth/dialog',
//                            'tokenUrl'         => 'https://example.com/api/oauth/token',
//                            'refreshUrl'       => 'https://example.com/api/oauth/refresh',
//                            'scopes'           => [
//                                'write:posts' => 'modify posts in your account',
//                                'read:posts'  => 'read your posts',
//                            ],
//                        ],
//                    ],
//                ],
//                'OpenId' => [
//                    'type'             => 'openIdConnect',
//                    'description'      => 'My openIdConnect Scheme description',
//                    'openIdConnectUrl' => 'https://example.com/api/oauth/openid',
//                ],
            ],

            /*
             * Root level security array, each entry should be a reference to a security scheme declared above
             * Examples commented below
             */
            'security' => [
//                'Bearer',
//                'ApiKey',
//                'OAuth2' => [
//                    'write:posts',
//                    'read:posts',
//                ],
//                'OpenId',
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
