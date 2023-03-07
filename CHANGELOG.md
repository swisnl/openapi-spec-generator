# Changelog

All notable changes to `openapi-spec-generator` will be documented in this file.

Updates should follow the [Keep a CHANGELOG](https://keepachangelog.com/) principles.

## [Unreleased]

- Nothing.


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
