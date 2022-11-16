# Changelog

This library adheres to [Semantic Versioning](https://semver.org/) and [Keep a CHANGELOG](https://keepachangelog.com/en/1.0.0/).

## Unreleased

### Added

- `ContainsString`, `DivisibleBy`, `FastFailValidatorChain`, and `WithMessage` validators.

### Fixed

- `Not::getMessages()` returned failure messages before first call to `::isValid()`.

## 1.1.0

### Added

- `Not` and `AnyValidator` validators.

### Fixed

- Incorrect value being passed to `BaseValidator::testValue()` in `BaseValidator::isValid()`.

## 1.0.0

Initial release.
