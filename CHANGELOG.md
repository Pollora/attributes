# Changelog

All notable changes to `pollora/attributes` are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- `Naming::headline()`, the words of a name with a capital each, as Laravel's `Str::headline()` writes them, for the packages that label a role or a binding source from its class name

## [0.1.0] - 2026-10-12

A first version for the framework's next release to build on; 1.0.0 comes with that release.

### Added

- First release, extracted from `pollora/framework` (`src/Attributes`) with its history. Class names are unchanged: `Pollora\Attributes\*`
- Declarations only: no attribute reaches Laravel or a framework service, which an architecture test keeps. Post type and taxonomy attributes implement `ConfiguresArguments`, method attributes `ConfiguresArgumentsWithMethod`, so the arguments they set are read from the attribute alone
- The value types the attributes take, under their framework class names: `Pollora\Schedule\Every` and `Interval`, `Pollora\Role\Domain\Enums\Access`, `Pollora\Meta\Domain\Enums\Control`, `Pollora\BlockBinding\Domain\Enums\BindingFieldType`
