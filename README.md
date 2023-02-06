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

## Generating Documentation

A quick way to preview your documentation is to use [Speccy](https://speccy.io/).
Ensure you have installed Speccy globally and then you can use the following command.

```
speccy serve storage/app/v1_openapi.yaml
```

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
[ico-swis]: https://img.shields.io/badge/%F0%9F%9A%80-made%20by%20SWIS-%230737A9.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/swisnl/openapi-spec-generator
[link-github-actions]: https://github.com/swisnl/openapi-spec-generator/actions/workflows/tests.yml
[link-downloads]: https://packagist.org/packages/swisnl/openapi-spec-generator
[link-treeware]: https://plant.treeware.earth/swisnl/openapi-spec-generator
[link-contributors]: ../../contributors
[link-swis]: https://www.swis.nl
