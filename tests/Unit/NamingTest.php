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
