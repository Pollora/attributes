<?php

declare(strict_types=1);

namespace Pollora\Attributes\Support;

/**
 * Derives the default names attributes fall back on: slugs, meta keys and
 * command names.
 *
 * Attributes are read without the framework, so they cannot reach Laravel's
 * Str helper. snake() and kebab() give the same results as Str::snake() and
 * Str::kebab(): a slug derived from a class name must not change.
 *
 * @internal
 */
final class Naming
{
    /**
     * The value in snake case, as Laravel's Str::snake() writes it.
     */
    public static function snake(string $value, string $delimiter = '_'): string
    {
        if (ctype_lower($value)) {
            return $value;
        }

        $value = (string) preg_replace('/\s+/u', '', ucwords($value));

        return mb_strtolower((string) preg_replace('/(.)(?=[A-Z])/u', '$1'.$delimiter, $value), 'UTF-8');
    }

    /**
     * The value in kebab case, as Laravel's Str::kebab() writes it.
     */
    public static function kebab(string $value): string
    {
        return self::snake($value, '-');
    }

    /**
     * The value as words with a capital each, as Laravel's Str::headline()
     * writes it: `EventManager`, `event_manager` and `event-manager` give
     * `Event Manager`.
     */
    public static function headline(string $value): string
    {
        $words = mb_split('\s+', $value) ?: [$value];
        $title = static fn (string $word): string => mb_convert_case($word, MB_CASE_TITLE, 'UTF-8');

        $parts = count($words) > 1
            ? array_map($title, $words)
            : array_map($title, preg_split('/(?=\p{Lu})/u', implode('_', $words), -1, PREG_SPLIT_NO_EMPTY) ?: []);

        $collapsed = str_replace(['-', '_', ' '], '_', implode('_', $parts));

        return implode(' ', array_filter(explode('_', $collapsed)));
    }

    /**
     * The class name without its namespace.
     */
    public static function classBasename(string $className): string
    {
        return basename(str_replace('\\', '/', $className));
    }

    /**
     * A command slug from a class name, without its "Command" suffix.
     */
    public static function commandFromClassName(string $className): string
    {
        $baseName = self::classBasename($className);

        if (str_ends_with($baseName, 'Command')) {
            $baseName = substr($baseName, 0, -7);
        }

        return self::slug($baseName);
    }

    /**
     * A command slug: a hyphen before each capital that follows a lowercase
     * letter or a digit, lowercased, hyphens collapsed and trimmed.
     */
    public static function slug(string $value): string
    {
        $value = strtolower((string) preg_replace('/([a-z0-9])([A-Z])/', '$1-$2', $value));

        return trim((string) preg_replace('/-+/', '-', $value), '-');
    }
}
