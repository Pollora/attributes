<?php

declare(strict_types=1);

use Illuminate\Support\Str;
use Pollora\Attributes\Support\Naming;

/**
 * Names derived from class, property and method names.
 *
 * Attributes no longer reach Laravel's Str, but a post type registered under a
 * slug derived from its class name must keep that slug: Naming has to agree
 * with Str on every shape of name.
 */
dataset('names', [
    'PascalCase class' => 'SyntheseDePresse',
    'single word' => 'Book',
    'already lower' => 'book',
    'camelCase property' => 'eventDate',
    'acronym' => 'HTMLParser',
    'digits' => 'Top10Books',
    'underscore' => 'event_date',
    'spaces' => 'event date time',
    'accented capital' => 'ÉvénementPublic',
    'trailing capital' => 'PostA',
]);

it('writes snake case as Str::snake() does', function (string $name): void {
    expect(Naming::snake($name))->toBe(Str::snake($name));
})->with('names');

it('writes kebab case as Str::kebab() does', function (string $name): void {
    expect(Naming::kebab($name))->toBe(Str::kebab($name));
})->with('names');

it('writes headlines as Str::headline() does', function (string $name): void {
    expect(Naming::headline($name))->toBe(Str::headline($name));
})->with('names')->with(['kebab-case' => 'event-manager', 'snake with digits' => 'event_manager_2', 'several words' => 'Event manager role', 'empty' => '']);

it('strips the namespace as class_basename() does', function (): void {
    expect(Naming::classBasename('App\\Cms\\PostTypes\\Book'))->toBe(class_basename('App\\Cms\\PostTypes\\Book'))
        ->and(Naming::classBasename('Book'))->toBe('Book');
});

it('derives command slugs, the Command suffix dropped', function (): void {
    expect(Naming::commandFromClassName('App\\Cli\\UserManagementCommand'))->toBe('user-management')
        ->and(Naming::commandFromClassName('Cache'))->toBe('cache')
        ->and(Naming::slug('deleteById'))->toBe('delete-by-id')
        ->and(Naming::slug('HTMLParser'))->toBe('htmlparser');
});

it('leaves an already lower case value untouched', function (): void {
    expect(Naming::snake('already_snake'))->toBe('already_snake')
        ->and(Naming::kebab('some-kebab'))->toBe('some-kebab');
});

it('returns an empty string rather than failing on invalid UTF-8', function (): void {
    expect(Naming::snake("\xffA"))->toBe('')
        ->and(Naming::kebab("\xffA"))->toBe('');
});

it('writes headlines from every separator', function (string $value, string $expected): void {
    expect(Naming::headline($value))->toBe($expected);
})->with([
    'hyphens' => ['event-manager', 'Event Manager'],
    'underscores' => ['event_manager', 'Event Manager'],
    'spaces' => ['event manager', 'Event Manager'],
    'two words' => ['event manager', 'Event Manager'],
    'one word' => ['event', 'Event'],
    'one capital word' => ['EventManager', 'Event Manager'],
    'repeated separators' => ['event--manager__role', 'Event Manager Role'],
    'leading and trailing' => ['_event_manager_', 'Event Manager'],
    'single separator only' => ['-', ''],
    'empty' => ['', ''],
]);

it('trims and collapses hyphens in a slug', function (): void {
    expect(Naming::slug('-Foo--Bar-'))->toBe('foo-bar')
        ->and(Naming::slug('fooBar'))->toBe('foo-bar')
        ->and(Naming::slug('foo9Bar'))->toBe('foo9-bar')
        ->and(Naming::slug('---'))->toBe('');
});
