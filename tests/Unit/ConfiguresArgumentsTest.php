<?php

declare(strict_types=1);

use Pollora\Attributes\Contracts\ConfiguresArguments;
use Pollora\Attributes\PostType\AdminCol;
use Pollora\Attributes\PostType\Chronological;
use Pollora\Attributes\PostType\HasArchive;
use Pollora\Attributes\PostType\Labels;
use Pollora\Attributes\PostType\RegisterMetaBoxCb;
use Pollora\Attributes\PostType\Rewrite;
use Pollora\Attributes\PostType\ShowInRest;
use Pollora\Attributes\Taxonomy\Args;
use Pollora\Attributes\Taxonomy\Labels as TaxonomyLabels;
use Pollora\Attributes\Taxonomy\MetaBoxCb;
use Pollora\Attributes\Taxonomy\ObjectType;
use Pollora\Attributes\Taxonomy\UpdateCountCallback;
use Pollora\Attributes\WpRestRoute\Method;

/**
 * A method a method attribute may decorate.
 *
 * @return array{0: object, 1: string}
 */
function decoratedMethod(): array
{
    return [new class
    {
        public function render(): string
        {
            return 'rendered';
        }
    }, 'render'];
}

/**
 * What each attribute contributes to the registration arguments, read from the
 * attribute alone: no discovery, no framework.
 */
it('sets the argument it stands for and keeps the others', function (ConfiguresArguments $attribute, array $expected): void {
    expect($attribute->configureArguments(['public' => true]))->toBe(['public' => true, ...$expected]);
})->with([
    'HasArchive' => [new HasArchive('library'), ['has_archive' => 'library']],
    'ShowInRest' => [new ShowInRest(false), ['show_in_rest' => false]],
    'Rewrite' => [new Rewrite(['slug' => 'books']), ['rewrite' => ['slug' => 'books']]],
    'Taxonomy Args' => [new Args(['orderby' => 'name']), ['args' => ['orderby' => 'name']]],
    'ObjectType' => [new ObjectType(['post', 'page']), ['object_type' => ['post', 'page']]],
    'Chronological' => [new Chronological, ['orderby' => 'date', 'order' => 'DESC']],
]);

it('replaces an argument set before it', function (): void {
    expect((new HasArchive(false))->configureArguments(['has_archive' => 'library']))->toBe(['has_archive' => false]);
});

it('adds nothing when switched off', function (): void {
    expect((new Chronological(false))->configureArguments(['public' => true]))->toBe(['public' => true]);
});

it('merges labels into those gathered so far', function (ConfiguresArguments $labels): void {
    $arguments = $labels->configureArguments(['labels' => ['name' => 'Books', 'all_items' => 'All Books']]);

    expect($arguments['labels'])->toBe(['name' => 'Books', 'all_items' => 'Every book', 'menu_name' => 'Library']);
})->with([
    'post type' => [new Labels(menuName: 'Library', allItems: 'Every book')],
    'taxonomy' => [new TaxonomyLabels(menuName: 'Library', allItems: 'Every book')],
]);

it('hands the decorated method to the argument it stands for', function (object $attribute, string $key): void {
    $callback = decoratedMethod();

    expect($attribute->configureArguments([], $callback))->toBe([$key => $callback]);
})->with([
    'RegisterMetaBoxCb' => [new RegisterMetaBoxCb, 'register_meta_box_cb'],
    'MetaBoxCb' => [new MetaBoxCb, 'meta_box_cb'],
    'UpdateCountCallback' => [new UpdateCountCallback, 'update_count_callback'],
]);

it('adds an admin column rendered by the decorated method, next to the existing ones', function (): void {
    $callback = decoratedMethod();

    $arguments = (new AdminCol('isbn', 'ISBN', sortable: true, width: 80))
        ->configureArguments(['admin_cols' => ['author' => ['title' => 'Author']]], $callback);

    expect($arguments['admin_cols'])->toBe([
        'author' => ['title' => 'Author'],
        'isbn' => ['title' => 'ISBN', 'function' => $callback, 'width' => 80, 'sortable' => true],
    ]);
});

it('accepts the HTTP verbs of a REST route, in any case', function (): void {
    expect((new Method('get'))->getMethods())->toBe(['get'])
        ->and((new Method(['POST', 'DELETE']))->getMethods())->toBe(['POST', 'DELETE']);
});

it('refuses an HTTP verb a REST route cannot use', function (): void {
    new Method(['GET', 'OPTIONS']);
})->throws(InvalidArgumentException::class, 'Invalid HTTP method "OPTIONS"');
