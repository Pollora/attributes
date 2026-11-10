<?php

declare(strict_types=1);

use Pollora\Attributes\Ability;
use Pollora\Attributes\BlockBinding;
use Pollora\Attributes\CapabilitySet;
use Pollora\Attributes\Meta;
use Pollora\Attributes\PostType;
use Pollora\Attributes\Role;
use Pollora\Attributes\Taxonomy;

/*
 * The fields of the attributes that hold text shown to a person, by attribute.
 * Everything else (slugs, keys, hooks, capabilities, formats, icons, class
 * names) stays out. Read as data by whatever translates or extracts these
 * strings: it needs nothing from this package but the file, which is declared
 * under `extra.pollora.i18n` in composer.json.
 *
 * A text parameter added to an attribute is listed here, or in the technical
 * list of tests/Unit/I18nDeclarationTest.php: that test fails until it is.
 */
return [
    Ability::class => ['label', 'description'],
    BlockBinding::class => ['label'],
    BlockBinding\BindingField::class => ['label'],
    CapabilitySet::class => ['label'],
    Meta::class => ['label', 'description', 'group'],
    PostType::class => ['singular', 'plural'],
    PostType\AdminCol::class => ['title'],
    PostType\Description::class => ['value'],
    PostType\FeaturedImage::class => ['value'],
    PostType\Label::class => ['value'],
    PostType\Labels::class => ['name', 'singularName', 'menuName', 'allItems', 'addNew', 'addNewItem', 'editItem', 'newItem', 'viewItem', 'viewItems', 'searchItems', 'notFound', 'notFoundInTrash', 'parentItemColon', 'archives', 'attributes', 'insertIntoItem', 'uploadedToThisItem', 'featuredImage', 'setFeaturedImage', 'removeFeaturedImage', 'useFeaturedImage', 'filterItemsList', 'itemsListNavigation', 'itemsList', 'itemPublished', 'itemUpdated', 'itemScheduled', 'itemReverted'],
    PostType\TitlePlaceholder::class => ['value'],
    Role::class => ['label'],
    Taxonomy::class => ['singular', 'plural'],
    Taxonomy\Description::class => ['value'],
    Taxonomy\Label::class => ['value'],
    Taxonomy\Labels::class => ['name', 'singularName', 'menuName', 'allItems', 'editItem', 'viewItem', 'updateItem', 'addNewItem', 'newItemName', 'searchItems', 'popularItems', 'separateItemsWithCommas', 'addOrRemoveItems', 'chooseFromMostUsed', 'notFound', 'parentItem', 'parentItemColon', 'noTerms', 'filterByItem', 'itemsListNavigation', 'itemsList', 'backToItems'],
];
