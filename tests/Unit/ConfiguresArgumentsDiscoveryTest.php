<?php

declare(strict_types=1);

use Pollora\Attributes\Contracts\ConfiguresArguments;
use Pollora\Attributes\Contracts\ConfiguresArgumentsWithMethod;

/**
 * Every class of src/ that implements one of the two contracts, found by
 * reading the directory: a new attribute is covered without touching this file.
 *
 * @return array<string, array{0: class-string}>
 */
function argumentConfiguringClasses(): array
{
    $root = dirname(__DIR__, 2).'/src';
    $classes = [];

    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root)) as $file) {
        if ($file->getExtension() !== 'php') {
            continue;
        }

        $class = 'Pollora\\Attributes\\'.str_replace('/', '\\', substr($file->getPathname(), strlen($root) + 1, -4));
        if (! class_exists($class)) {
            continue;
        }

        $reflection = new ReflectionClass($class);
        if ($reflection->isInstantiable() && ($reflection->implementsInterface(ConfiguresArguments::class) || $reflection->implementsInterface(ConfiguresArgumentsWithMethod::class))) {
            $classes[substr($class, strlen('Pollora\\Attributes\\'))] = [$class];
        }
    }

    ksort($classes);

    return $classes;
}

/**
 * The classes whose constructor takes a union type, which no single value can
 * stand for: their arguments are listed here.
 *
 * @return array<class-string, list<mixed>>
 */
function explicitArguments(): array
{
    return [
        'Pollora\\Attributes\\PostType\\TemplateLock' => ['all'],
        'Pollora\\Attributes\\Taxonomy\\DefaultTerm' => ['uncategorized'],
        'Pollora\\Attributes\\Taxonomy\\ObjectType' => [['post', 'page']],
    ];
}

/**
 * A valid value for each required constructor parameter, from its type.
 */
function argumentFor(ReflectionParameter $parameter): mixed
{
    $type = $parameter->getType();

    return match ($type instanceof ReflectionNamedType ? $type->getName() : null) {
        'bool' => true,
        'int' => 1,
        'string' => 'value',
        'array' => ['key' => 'value'],
        default => throw new LogicException("No generic value for \${$parameter->getName()} of {$parameter->getDeclaringClass()?->getName()}: list the class explicitly."),
    };
}

/**
 * @param  class-string  $class
 */
function newArgumentConfiguringAttribute(string $class): object
{
    if (isset(explicitArguments()[$class])) {
        return new $class(...explicitArguments()[$class]);
    }

    $constructor = (new ReflectionClass($class))->getConstructor();
    $arguments = $constructor === null ? [] : array_map(
        argumentFor(...),
        array_values(array_filter($constructor->getParameters(), fn (ReflectionParameter $parameter): bool => ! $parameter->isOptional())),
    );

    return new $class(...$arguments);
}

it('finds the attributes that configure arguments', function (): void {
    expect(argumentConfiguringClasses())->not->toBeEmpty();
});

it('writes at least one argument', function (string $class): void {
    $attribute = newArgumentConfiguringAttribute($class);
    $result = $attribute instanceof ConfiguresArgumentsWithMethod
        ? $attribute->configureArguments([], fn (): string => 'rendered')
        : $attribute->configureArguments([]);

    expect($result)->not->toBeEmpty();
})->with(argumentConfiguringClasses());

it('keeps the arguments gathered before it', function (string $class): void {
    $attribute = newArgumentConfiguringAttribute($class);
    $result = $attribute instanceof ConfiguresArgumentsWithMethod
        ? $attribute->configureArguments(['sentinel' => 'kept'], fn (): string => 'rendered')
        : $attribute->configureArguments(['sentinel' => 'kept']);

    expect($result)->toHaveKey('sentinel', 'kept');
})->with(argumentConfiguringClasses());
