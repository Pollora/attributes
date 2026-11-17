# Changelog

All notable changes to `pollora/attributes` are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.1.3] - 2026-11-17

### Added

- **`#[Ajax]` takes a `nonce` parameter**, to say how an AJAX action is protected against cross-site request forgery: `nonce: true` verifies a nonce named after the action, `nonce: 'notes_form'` verifies the nonce of that name, `nonce: false` turns it off on purpose, and the default `null` has not chosen yet. The attribute only carries the value; the framework's `AjaxDiscovery` passes it on to `pollora/ajax` 1.2, where an action that has not chosen still runs without a check in the 1.x versions and is reported, and the next major version verifies a nonce by default. The parameter is a technical text parameter, never translated, and is added to the API surface.

## [0.1.2] - 2026-11-12

### Added

- `resources/i18n.php`, declared under `extra.pollora.i18n` in `composer.json`: the fields of the attributes that hold text shown to a person (`singular`, `plural`, labels, descriptions, column titles, placeholders…), as plain data a translation tool reads with no dependency on this package. A test fails when a text parameter is added to an attribute without being classed as translatable or technical

### Changed

- The harness's ARCH-08 rule (attribute classes are final, with `readonly` public properties) is exempted through `allow_rules` in `.harness.json`, for this repository only: the classes are published and may be extended, so making them `final` and `readonly` is a change of contract, planned for the next major version. Every other architecture rule stays active

## [0.1.1] - 2026-10-15

### Added

- `Naming::headline()`, the words of a name with a capital each, as Laravel's `Str::headline()` writes them, for the packages that label a role or a binding source from its class name

## [0.1.0] - 2026-10-12

A first version for the framework's next release to build on; 1.0.0 comes with that release.

### Added

- First release, extracted from `pollora/framework` (`src/Attributes`) with its history. Class names are unchanged: `Pollora\Attributes\*`
- Declarations only: no attribute reaches Laravel or a framework service, which an architecture test keeps. Post type and taxonomy attributes implement `ConfiguresArguments`, method attributes `ConfiguresArgumentsWithMethod`, so the arguments they set are read from the attribute alone
- The value types the attributes take, under their framework class names: `Pollora\Schedule\Every` and `Interval`, `Pollora\Role\Domain\Enums\Access`, `Pollora\Meta\Domain\Enums\Control`, `Pollora\BlockBinding\Domain\Enums\BindingFieldType`
