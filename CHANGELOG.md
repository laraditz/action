# Changelog

All notable changes to this project will be documented in this file.

## [2.0.0] - 2025-06-09

### Changed

- Revamp the Action class and make it simpler to use.
- Pass arguments through constructor and use constructor property promotion.

### Added

- Add support for Laravel 9, 10, 11, 12 and remove support for PHP 7.

## [1.0.2] - 2020-08-23

### Added

- Add dependency injection support for handle method.

## [1.0.1] - 2020-08-18

### Changed

- Change `now` to `dispatch` method.

## [1.0.0] - 2020-06-19

### Added

- Initial commit.
- Add Action service provider and Action class.
- Add Resolvable trait.
- Add config.
- Add `make:action` command.
