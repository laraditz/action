# Changelog

All notable changes to this project will be documented in this file.

## [2.1.0] - 2026-06-25

### Added

- Add support for Laravel 13.
- Re-add method injection on `handle()` via `app()->call()`.
- Add explicit static `dispatch()` method for queue dispatching.
- Add `illuminate/bus` as explicit dependency.

### Changed

- Replace instance `__call` magic with container-aware `run()` — both `$action->run()` and `ClassName::run(...)` now inject dependencies into `handle()`.
- `__callStatic` now only handles the static `run()` pattern, delegating to instance `run()`.
- Bump PHP minimum requirement to `^8.2`.
- Add null-guard to `data()` for actions with no explicit constructor.

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
