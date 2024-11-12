# Changelog

All notable changes to `openapi-spec-generator` will be documented in this file.

Updates should follow the [Keep a CHANGELOG](https://keepachangelog.com/) principles.

## [Unreleased]

- Nothing

## [0.7.0] - 2024-11-12

### Added
- Add support for doc generation for non-eloquent resources [#18](https://github.com/swisnl/openapi-spec-generator/pull/18).

### Changed
- Dropped PHP 7 support.

### Fixed
- Use filter column name instead of filter key to retrieve example data [#20](https://github.com/swisnl/openapi-spec-generator/pull/20).


## [0.6.1] - 2024-05-15

### Added
- Add support for Laravel JSON:API v4 and Laravel 11 [#19](https://github.com/swisnl/openapi-spec-generator/pull/19).


## [0.6.0] - 2023-03-29

### Added
- Add support for enums in filter examples.

### Changed
- A meaningful exception is thrown when you forget to seed the database [#11](https://github.com/swisnl/openapi-spec-generator/pull/11).

### Fixed
- Fall back to a basic descriptor when no custom filter descriptor is found.
- Use field column name to get example value.


## [0.5.1] - 2023-03-07

### Added
- Add support for Laravel JSON:API v3 and Laravel 10 [#9](https://github.com/swisnl/openapi-spec-generator/pull/9).


## [0.5.0] - 2023-02-06

### Added
- Allow customizing the storage disk to use [#6](https://github.com/swisnl/openapi-spec-generator/pull/6).
- Add support for `Has`, `WhereNull` and `WhereNotNull` filters.

### Fixed
- Use correct description for `WherePivotNotIn` filter.


## [0.4.0] - 2022-02-24

### Added
- Allow developers to add descriptions to endpoints [#2](https://github.com/swisnl/openapi-spec-generator/pull/2).

### Changed
- Require `laravel-json-api/laravel` version 2 [#2](https://github.com/swisnl/openapi-spec-generator/pull/2).

### Fixed
- Fix a wrongly generated doc for to many relationships [#1](https://github.com/swisnl/openapi-spec-generator/pull/1).
