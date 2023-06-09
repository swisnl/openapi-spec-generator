# OpenAPI v3 Spec Generator

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Buy us a tree][ico-treeware]][link-treeware]
[![Build Status][ico-github-actions]][link-github-actions]
[![Total Downloads][ico-downloads]][link-downloads]
[![Maintained by SWIS][ico-swis]][link-swis]

Designed to work with [Laravel JSON:API](https://laraveljsonapi.io/)

!!! Disclaimer: this project is work in progress and likely contains many bugs, etc !!!

## What it can and can't

### Can

- [x] Generate Schemas/Responses/Request/Errors for all default [Laravel JSON:API](https://laraveljsonapi.io/) routes
- [x] Use a seeded database to generate examples

### Can't yet
- [ ] Customisation of the generation
- [ ] Generation for custom actions
- [ ] Generation for custom filters
- [ ] Generation for anything custom
- [ ] Generation for MorphTo relations (MorphToMany works)
- [ ] Generation of Pagination Meta
- [ ] Generation of Includes
- [ ] Generation of Authentication/Authorization

## TODO

- [x] Command to generate to storage folder
- [x] Get basic test suite running with GitHub Actions
- [x] Add extra operation descriptions via config
- [x] Add in tags & x-tagGroups (via config)
- [x] Add tests (Use the dummy by laraveljsonapi to integrate all features)
- [ ] Add custom actions
- [x] Split schemas/requests/responses by action
- [ ] Consider field attributes
  - [x] bool readonly
  - [x] bool hidden
  - [ ] closure based readonly (create/update)
  - [ ] closure based hidden
- [x] List sortable fields
- [ ] Fix includes and relations
  - [x] Add relationship routes
  - [ ] Add includes
- [ ] Add authentication
- [ ] Add custom queries/filters
- [ ] Add a way to document custom actions
- [ ] Tidy up the code!!
- [x] Replace `cebe/php-openapi` with `goldspecdigital/oooas`
- [x] Move to an architecture inspired by `vyuldashev/laravel-openapi`
- [ ] Use php8 attributes on actions/classes to generate custom docs

🙏 Based upon initial prototype by [martianatwork](https://github.com/martianatwork), [glennjacobs](https://github.com/glennjacobs) and [byte-it](https://github.com/byte-it).

## Install

Via Composer
```
composer require swisnl/openapi-spec-generator
```

Publish the config file

```
php artisan vendor:publish --provider="LaravelJsonApi\OpenApiSpec\OpenApiServiceProvider"
```

## Usage

Generate the Open API spec
```
php artisan jsonapi:openapi:generate v1
```

Note that a seeded DB is required! The seeded data will be used to generate Samples.

### Descriptions

It's possible to add descriptions to your endpoints by implementing the DescribesEndpoints interface. The added method
receives the generated route name as a parameter. This can be used to generate descriptions for all your schema
endpoints.
``` php
class Post extends Schema implements DescribesEndpoints
{
    public function describeEndpoint(string $endpoint) {
        if ($endpoint === 'v1.posts.index') {
            return 'Description for index method';
        }

        return 'Default description';
    }
}
```

### Security schemes & requirements

It is possible to declare security schemes and requirements for each server using our config file.  
Examples of each security scheme can be found in the config file.

`config/openapi.php`:
``` php
return [
    'servers'         => [
        'v1' => [
            //...

            'securitySchemes' => [
                'MyBearerScheme' => [
                    'type'         => 'http',
                    'description'  => 'Example scheme instructions, can be done in Markdown for long / formatted descriptions',
                    'scheme'       => 'bearer',
                    'bearerFormat' => 'JWT',
                ],
            ],

            'security' => [
                'MyBearerScheme',
            ],
            
            //...
        ],
    ],
    //...
```

## Generating Documentation

### [Speccy](https://github.com/wework/speccy)

A quick way to preview your documentation is to use [`speccy serve` command](https://github.com/wework/speccy#serve-command).

Ensure you have installed Speccy globally and then you can use the following command:

```sh
speccy serve storage/app/v1_openapi.yaml
```

> Warning: Seems like [Speccy](https://speccy.io) is abandoned (https://github.com/wework/speccy/issues/485).

### [Laravel Stoplight Elements](https://github.com/JustSteveKing/laravel-stoplight-elements)

Easily publish your API documentation in a local route by using your OpenAPI document in your Laravel Application directly.

> For this to work, you have to generate your spec in a public-available location, like the local 'public' disk available in Laravel applications:
> ```sh
> OPEN_API_SPEC_GENERATOR_FILESYSTEM_DISK='public'
> ```

After [installing it](https://github.com/JustSteveKing/laravel-stoplight-elements#laravel-stoplight-elements), you should set its url config: `STOPLIGHT_OPENAPI_PATH`. For example, if you're using the 'public' disk:

```sh
OPEN_API_SPEC_GENERATOR_FILESYSTEM_DISK='public'

# '/storage' is the default 'public' URL.
STOPLIGHT_OPENAPI_PATH='/storage/v1_openapi.json' 
```

> Note: If you need a more dynamic way to get access to the spec URL (for example, in S3 you may need to use [temporary URLs](https://laravel.com/docs/filesystem#temporary-urls)), you can publish its Blade template and [replace some lines ](https://github.com/JustSteveKing/laravel-stoplight-elements/blob/2.0.0/resources/views/docs.blade.php#L14) to generate your own URI. Also, you may need to add an Fetch interceptor to integrate it with your authentication methods.

With its default route, ¡you just need to access to your `/api/docs` route to preview your specs! :bowtie:

Check [its configuration docs](https://github.com/JustSteveKing/laravel-stoplight-elements#configuration) for further options.

### [Standalone Stoplight Elements Web Component](https://github.com/stoplightio/elements#web-component)

In addition to previous Laravel package, you can use the Stoplight Elements by yourself. It is available as React Component, or Web Component, making it easier for integrating into existing Content Management Systems with their own navigation.

This is useful when you need more advanced customizations in the routing system, integrate it in your existing Vue|React|Vanilla application, or publish it as a non-laravel static HTML site. But... you have to setup it manually. :sweat_smile:

#### Web component integrated in your Vue application

You can [follow the instructions](https://github.com/stoplightio/elements/blob/main/docs/getting-started/elements/html.md) to use the standalone Web Component, grab it into a blade template and armor your view.

It has [advanced options](https://github.com/stoplightio/elements/blob/main/docs/getting-started/elements/elements-options.md), like `tryItCredentialPolicy="same-origin"` to use your cookie-based authentication (like [Sanctum](https://github.com/laravel/sanctum/)).

Also, in your Blade view or Vue's app initializer, as this package uses [Fetch API](https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API) you can add interceptors to customize the "try it out" feature, like adding default headers for `Content-Type` and/or `Accept` to be `'application/vnd.api+json'` to your requests.

### Other options

You can check a more exhaustive list of options available at https://openapi.tools/#documentation

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email security@swis.nl instead of using the issue tracker.

## Credits

- [Glenn Jacobs](https://github.com/glennjacobs)
- [Johannes Kees](https://github.com/byte-it)
- [Björn Brala](https://github.com/bbrala)
- [Rien van Velzen](https://github.com/Rockheep)
- [All Contributors][link-contributors]

## License

Apache License 2.0. All notable changes to the original work can be found in [CHANGELOG](CHANGELOG.md). Please see [License File](LICENSE.md) for more information.

This package is [Treeware](https://treeware.earth). If you use it in production, then we ask that you [**buy the world a tree**][link-treeware] to thank us for our work. By contributing to the Treeware forest you’ll be creating employment for local families and restoring wildlife habitats.

## SWIS :heart: Open Source

[SWIS][link-swis] is a web agency from Leiden, the Netherlands. We love working with open source software.

[ico-version]: https://img.shields.io/packagist/v/swisnl/openapi-spec-generator.svg?style=flat-square
[ico-license]: https://img.shields.io/packagist/l/swisnl/openapi-spec-generator?style=flat-square
[ico-treeware]: https://img.shields.io/badge/Treeware-%F0%9F%8C%B3-lightgreen.svg?style=flat-square
[ico-github-actions]: https://img.shields.io/github/actions/workflow/status/swisnl/openapi-spec-generator/tests.yml?label=tests&branch=master&style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/swisnl/openapi-spec-generator.svg?style=flat-square
[ico-swis]: https://img.shields.io/badge/%F0%9F%9A%80-maintained%20by%20SWIS-%230737A9.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/swisnl/openapi-spec-generator
[link-github-actions]: https://github.com/swisnl/openapi-spec-generator/actions/workflows/tests.yml
[link-downloads]: https://packagist.org/packages/swisnl/openapi-spec-generator
[link-treeware]: https://plant.treeware.earth/swisnl/openapi-spec-generator
[link-contributors]: ../../contributors
[link-swis]: https://www.swis.nl
