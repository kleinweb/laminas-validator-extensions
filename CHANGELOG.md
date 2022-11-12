# Changelog

This library adheres to [Semantic Versioning](https://semver.org/) and [Keep a CHANGELOG](https://keepachangelog.com/en/1.0.0/).

## Unreleased

### Added

- `ContainsString`, `DivisibleBy`, `FastValidatorChain`, `ValidatorByOperator`, and `WithMessage` validators.

### Changed

- The failure message returned by `Not::getMessages()` now has the identifier `notValid`.

### Fixed

- `Not::getMessages()` returned failure messages before first call to `::isValid()`.
- `Not::getMessages()` returned an indexed array of messages.

## 1.1.0

### Added

- `Not` and `AnyValidator` validators.

### Fixed

- Incorrect value being passed to `BaseValidator::testValue()` in `BaseValidator::isValid()`.

## 1.0.0

Initial release.
