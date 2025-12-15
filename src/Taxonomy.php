<?php

declare(strict_types=1);

namespace Pollora\Attributes;

use Attribute;

/**
 * Attribute for defining custom taxonomies.
 *
 * This attribute can be applied to classes to define them as WordPress custom taxonomies.
 * It works with the TaxonomyDiscovery service for registration.
 *
 * @example
 * ```php
 * #[Taxonomy]
 * class Category {}
 *
 * #[Taxonomy('product-type')]
 * class ProductType {}
 *
 * #[Taxonomy('event-category', singular: 'Event Category', plural: 'Event Categories')]
 * class EventCategory {}
 *
 * #[Taxonomy('tag', objectType: ['post', 'page'])]
 * class CustomTag {}
 *
 * #[Taxonomy('genre', singular: 'Genre', plural: 'Genres', objectType: 'book')]
 * class BookGenre {}
 * ```
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Taxonomy
{
    /**
     * Create a new Taxonomy attribute instance.
     *
     * @param  string|null  $slug  The taxonomy slug. If null, will be auto-generated from class name using kebab-case.
     * @param  string|null  $singular  The singular name for the taxonomy. If null, will be auto-generated from class name.
     * @param  string|null  $plural  The plural name for the taxonomy. If null, will be auto-generated from singular name.
     * @param  array<string>|string|null  $objectType  The post types this taxonomy applies to. If null, defaults to ['post'].
     */
    public function __construct(
        public ?string $slug = null,
        public ?string $singular = null,
        public ?string $plural = null,
        public array|string|null $objectType = null
    ) {}

}
