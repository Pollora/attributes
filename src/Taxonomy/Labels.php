<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Taxonomy\Domain\Contracts\TaxonomyAttributeInterface;

/**
 * Attribute to set custom labels for a taxonomy.
 *
 * Labels provided here are merged with auto-generated labels,
 * allowing partial overrides with named parameters:
 *
 * ```php
 * #[Labels(
 *     allItems: 'All Categories',
 *     editItem: 'Edit Category',
 * )]
 * ```
 *
 * For translatable labels, use withArgs() where __() can be
 * evaluated at runtime (PHP attributes only accept constants).
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Labels extends TaxonomyAttribute
{
    /** @var array<string, string> */
    private readonly array $labels;

    public function __construct(
        ?string $name = null,
        ?string $singularName = null,
        ?string $menuName = null,
        ?string $allItems = null,
        ?string $editItem = null,
        ?string $viewItem = null,
        ?string $updateItem = null,
        ?string $addNewItem = null,
        ?string $newItemName = null,
        ?string $searchItems = null,
        ?string $popularItems = null,
        ?string $separateItemsWithCommas = null,
        ?string $addOrRemoveItems = null,
        ?string $chooseFromMostUsed = null,
        ?string $notFound = null,
        ?string $parentItem = null,
        ?string $parentItemColon = null,
        ?string $noTerms = null,
        ?string $filterByItem = null,
        ?string $itemsListNavigation = null,
        ?string $itemsList = null,
        ?string $backToItems = null,
    ) {
        $this->labels = array_filter([
            'name' => $name,
            'singular_name' => $singularName,
            'menu_name' => $menuName,
            'all_items' => $allItems,
            'edit_item' => $editItem,
            'view_item' => $viewItem,
            'update_item' => $updateItem,
            'add_new_item' => $addNewItem,
            'new_item_name' => $newItemName,
            'search_items' => $searchItems,
            'popular_items' => $popularItems,
            'separate_items_with_commas' => $separateItemsWithCommas,
            'add_or_remove_items' => $addOrRemoveItems,
            'choose_from_most_used' => $chooseFromMostUsed,
            'not_found' => $notFound,
            'parent_item' => $parentItem,
            'parent_item_colon' => $parentItemColon,
            'no_terms' => $noTerms,
            'filter_by_item' => $filterByItem,
            'items_list_navigation' => $itemsListNavigation,
            'items_list' => $itemsList,
            'back_to_items' => $backToItems,
        ], fn (?string $v): bool => $v !== null);
    }

    protected function configure(TaxonomyAttributeInterface $taxonomy): void
    {
        $existing = $taxonomy->attributeArgs['labels'] ?? [];
        $taxonomy->attributeArgs['labels'] = array_merge($existing, $this->labels);
    }
}
