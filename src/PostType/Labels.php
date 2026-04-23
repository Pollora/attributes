<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Domain\Contracts\PostTypeAttributeInterface;

/**
 * Attribute to set custom labels for a post type.
 *
 * Labels provided here are merged with auto-generated labels,
 * allowing partial overrides with named parameters:
 *
 * ```php
 * #[Labels(
 *     allItems: 'All Projects',
 *     addNew: 'Add Project',
 * )]
 * ```
 *
 * For runtime translations, use withArgs() in the class instead.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Labels extends PostTypeAttribute
{
    /** @var array<string, string> */
    private readonly array $labels;

    public function __construct(
        ?string $name = null,
        ?string $singularName = null,
        ?string $menuName = null,
        ?string $allItems = null,
        ?string $addNew = null,
        ?string $addNewItem = null,
        ?string $editItem = null,
        ?string $newItem = null,
        ?string $viewItem = null,
        ?string $viewItems = null,
        ?string $searchItems = null,
        ?string $notFound = null,
        ?string $notFoundInTrash = null,
        ?string $parentItemColon = null,
        ?string $archives = null,
        ?string $attributes = null,
        ?string $insertIntoItem = null,
        ?string $uploadedToThisItem = null,
        ?string $featuredImage = null,
        ?string $setFeaturedImage = null,
        ?string $removeFeaturedImage = null,
        ?string $useFeaturedImage = null,
        ?string $filterItemsList = null,
        ?string $itemsListNavigation = null,
        ?string $itemsList = null,
        ?string $itemPublished = null,
        ?string $itemUpdated = null,
        ?string $itemScheduled = null,
        ?string $itemReverted = null,
    ) {
        $this->labels = array_filter([
            'name' => $name,
            'singular_name' => $singularName,
            'menu_name' => $menuName,
            'all_items' => $allItems,
            'add_new' => $addNew,
            'add_new_item' => $addNewItem,
            'edit_item' => $editItem,
            'new_item' => $newItem,
            'view_item' => $viewItem,
            'view_items' => $viewItems,
            'search_items' => $searchItems,
            'not_found' => $notFound,
            'not_found_in_trash' => $notFoundInTrash,
            'parent_item_colon' => $parentItemColon,
            'archives' => $archives,
            'attributes' => $attributes,
            'insert_into_item' => $insertIntoItem,
            'uploaded_to_this_item' => $uploadedToThisItem,
            'featured_image' => $featuredImage,
            'set_featured_image' => $setFeaturedImage,
            'remove_featured_image' => $removeFeaturedImage,
            'use_featured_image' => $useFeaturedImage,
            'filter_items_list' => $filterItemsList,
            'items_list_navigation' => $itemsListNavigation,
            'items_list' => $itemsList,
            'item_published' => $itemPublished,
            'item_updated' => $itemUpdated,
            'item_scheduled' => $itemScheduled,
            'item_reverted_to_draft' => $itemReverted,
        ], fn (?string $v): bool => $v !== null);
    }

    protected function configure(PostTypeAttributeInterface $postType): void
    {
        $existing = $postType->attributeArgs['labels'] ?? [];
        $postType->attributeArgs['labels'] = array_merge($existing, $this->labels);
    }
}
