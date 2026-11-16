<?php

declare(strict_types=1);

use Pollora\Attributes\PostType\Labels as PostTypeLabels;
use Pollora\Attributes\Taxonomy\Labels as TaxonomyLabels;

/**
 * Each constructor parameter feeds exactly one WordPress label key.
 */
dataset('post type labels', [
    'name' => ['name', 'name'],
    'singularName' => ['singularName', 'singular_name'],
    'menuName' => ['menuName', 'menu_name'],
    'allItems' => ['allItems', 'all_items'],
    'addNew' => ['addNew', 'add_new'],
    'addNewItem' => ['addNewItem', 'add_new_item'],
    'editItem' => ['editItem', 'edit_item'],
    'newItem' => ['newItem', 'new_item'],
    'viewItem' => ['viewItem', 'view_item'],
    'viewItems' => ['viewItems', 'view_items'],
    'searchItems' => ['searchItems', 'search_items'],
    'notFound' => ['notFound', 'not_found'],
    'notFoundInTrash' => ['notFoundInTrash', 'not_found_in_trash'],
    'parentItemColon' => ['parentItemColon', 'parent_item_colon'],
    'archives' => ['archives', 'archives'],
    'attributes' => ['attributes', 'attributes'],
    'insertIntoItem' => ['insertIntoItem', 'insert_into_item'],
    'uploadedToThisItem' => ['uploadedToThisItem', 'uploaded_to_this_item'],
    'featuredImage' => ['featuredImage', 'featured_image'],
    'setFeaturedImage' => ['setFeaturedImage', 'set_featured_image'],
    'removeFeaturedImage' => ['removeFeaturedImage', 'remove_featured_image'],
    'useFeaturedImage' => ['useFeaturedImage', 'use_featured_image'],
    'filterItemsList' => ['filterItemsList', 'filter_items_list'],
    'itemsListNavigation' => ['itemsListNavigation', 'items_list_navigation'],
    'itemsList' => ['itemsList', 'items_list'],
    'itemPublished' => ['itemPublished', 'item_published'],
    'itemUpdated' => ['itemUpdated', 'item_updated'],
    'itemScheduled' => ['itemScheduled', 'item_scheduled'],
    'itemReverted' => ['itemReverted', 'item_reverted_to_draft'],
]);

dataset('taxonomy labels', [
    'name' => ['name', 'name'],
    'singularName' => ['singularName', 'singular_name'],
    'menuName' => ['menuName', 'menu_name'],
    'allItems' => ['allItems', 'all_items'],
    'editItem' => ['editItem', 'edit_item'],
    'viewItem' => ['viewItem', 'view_item'],
    'updateItem' => ['updateItem', 'update_item'],
    'addNewItem' => ['addNewItem', 'add_new_item'],
    'newItemName' => ['newItemName', 'new_item_name'],
    'searchItems' => ['searchItems', 'search_items'],
    'popularItems' => ['popularItems', 'popular_items'],
    'separateItemsWithCommas' => ['separateItemsWithCommas', 'separate_items_with_commas'],
    'addOrRemoveItems' => ['addOrRemoveItems', 'add_or_remove_items'],
    'chooseFromMostUsed' => ['chooseFromMostUsed', 'choose_from_most_used'],
    'notFound' => ['notFound', 'not_found'],
    'parentItem' => ['parentItem', 'parent_item'],
    'parentItemColon' => ['parentItemColon', 'parent_item_colon'],
    'noTerms' => ['noTerms', 'no_terms'],
    'filterByItem' => ['filterByItem', 'filter_by_item'],
    'itemsListNavigation' => ['itemsListNavigation', 'items_list_navigation'],
    'itemsList' => ['itemsList', 'items_list'],
    'backToItems' => ['backToItems', 'back_to_items'],
]);

it('maps each post type label parameter to its own key and nothing else', function (string $parameter, string $key): void {
    $labels = new PostTypeLabels(...[$parameter => 'Value']);

    expect($labels->configureArguments([]))->toBe(['labels' => [$key => 'Value']]);
})->with('post type labels');

it('maps each taxonomy label parameter to its own key and nothing else', function (string $parameter, string $key): void {
    $labels = new TaxonomyLabels(...[$parameter => 'Value']);

    expect($labels->configureArguments([]))->toBe(['labels' => [$key => 'Value']]);
})->with('taxonomy labels');

it('sets no label when no parameter is given', function (string $class): void {
    expect((new $class)->configureArguments(['public' => true]))->toBe(['public' => true, 'labels' => []]);
})->with([PostTypeLabels::class, TaxonomyLabels::class]);

it('keeps an empty string label, which is not null', function (string $class): void {
    expect((new $class(name: ''))->configureArguments([])['labels'])->toBe(['name' => '']);
})->with([PostTypeLabels::class, TaxonomyLabels::class]);

it('overrides generated labels and keeps the others', function (string $class): void {
    $arguments = (new $class(allItems: 'Mine'))->configureArguments(['labels' => ['all_items' => 'Generated', 'name' => 'Books'], 'public' => true]);

    expect($arguments)->toBe(['labels' => ['all_items' => 'Mine', 'name' => 'Books'], 'public' => true]);
})->with([PostTypeLabels::class, TaxonomyLabels::class]);
