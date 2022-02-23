# OpenAPI v3 Spec Generator

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

🙏 Based upon initial prototype by [martianatwork](https://github.com/martianatwork) and [glennjacobs](https://github.com/glennjacobs)

## Usage

Install package
```
composer require byteit/openapi-spec-generator
```

Publish the config file

```
php artisan vendor:publish --provider="LaravelJsonApi\OpenApiSpec\OpenApiServiceProvider"
```

Generate the Open API spec
```
php artisan jsonapi:openapi:generate v1
```

Note that a seeded DB is required! The seeded data will be used to generate Samples.

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


