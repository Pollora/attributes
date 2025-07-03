<?php

declare(strict_types=1);

namespace Pollora\Attributes;

use Attribute;
use Illuminate\Support\Str;
use Pollora\Attributes\Attributable;
use Pollora\Attributes\Contracts\AttributeCompatibility;
use Pollora\Attributes\Contracts\AttributeCompatibilityTrait;
use Pollora\Attributes\Contracts\HandlesAttributes;
use Pollora\Attributes\Contracts\TypedAttribute;
use ReflectionClass;
use ReflectionMethod;

/**
 * Attribute for defining custom taxonomies with domain support.
 *
 * This attribute can be applied to classes to define them as WordPress custom taxonomies.
 * It implements the new domain-based attribute system with proper isolation and handling.
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
final readonly class Taxonomy implements AttributeCompatibility, HandlesAttributes, TypedAttribute
{
    use AttributeCompatibilityTrait;

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

    /**
     * {@inheritDoc}
     */
    public function handle(
        mixed $container,
        Attributable $instance,
        ReflectionClass|ReflectionMethod $reflection,
        object $attribute
    ): void {
        if (! $reflection instanceof ReflectionClass) {
            return;
        }

        $className = $reflection->getName();

        // Prepare taxonomy data for the domain
        $taxonomyData = [
            'slug' => $this->getSlug($className),
            'singular' => $this->getSingular($className),
            'plural' => $this->getPlural($className),
            'object_type' => $this->getObjectType(),
        ];

        // Merge data into the taxonomy domain
        $instance->mergeDataForDomain('taxonomy', $taxonomyData);
    }

    /**
     * {@inheritDoc}
     */
    public function getPriority(): int
    {
        return 0; // Highest priority - base taxonomy definition
    }

    /**
     * {@inheritDoc}
     */
    public function isCombinable(): bool
    {
        return false; // Only one Taxonomy attribute per class
    }

    /**
     * {@inheritDoc}
     */
    public function getDomain(): string
    {
        return 'taxonomy';
    }

    /**
     * {@inheritDoc}
     */
    public function getIncompatibleDomains(): array
    {
        // Taxonomy is incompatible with post_type
        return ['post_type'];
    }

    /**
     * {@inheritDoc}
     */
    public function getSupportedDomains(): array
    {
        return ['taxonomy'];
    }

    /**
     * {@inheritDoc}
     */
    public function supportsDomain(string $domain): bool
    {
        return $domain === 'taxonomy';
    }

    /**
     * Get the taxonomy slug for the given class.
     *
     * If slug is explicitly provided in the attribute, use it.
     * Otherwise, auto-generate from the class name using kebab-case.
     *
     * @param  string  $className  The fully qualified class name
     * @return string The taxonomy slug
     */
    public function getSlug(string $className): string
    {
        if ($this->slug !== null) {
            return $this->slug;
        }

        // Get the class name without namespace
        $baseName = class_basename($className);

        // Convert to kebab-case
        return Str::kebab($baseName);
    }

    /**
     * Get the singular name for the taxonomy.
     *
     * If singular name is explicitly provided in the attribute, use it.
     * Otherwise, auto-generate from the class name.
     *
     * @param  string  $className  The fully qualified class name
     * @return string The singular name
     */
    public function getSingular(string $className): string
    {
        if ($this->singular !== null) {
            return $this->singular;
        }

        // Get the class name without namespace
        $baseName = class_basename($className);

        // Convert to snake_case first
        $snakeCase = Str::snake($baseName);

        // Then humanize it (convert snake_case to words with spaces and capitalize first letter)
        $humanized = ucfirst(str_replace('_', ' ', $snakeCase));

        // Ensure it's singular
        return Str::singular($humanized);
    }

    /**
     * Get the plural name for the taxonomy.
     *
     * If plural name is explicitly provided in the attribute, use it.
     * Otherwise, auto-generate by pluralizing the singular name.
     *
     * @param  string  $className  The fully qualified class name
     * @return string The plural name
     */
    public function getPlural(string $className): string
    {
        if ($this->plural !== null) {
            return $this->plural;
        }

        return Str::plural($this->getSingular($className));
    }

    /**
     * Get the object types (post types) this taxonomy applies to.
     *
     * If objectType is explicitly provided in the attribute, use it.
     * Otherwise, default to ['post'].
     *
     * @return array<string>|string The object types
     */
    public function getObjectType(): array|string
    {
        if ($this->objectType !== null) {
            return $this->objectType;
        }

        // Default to 'post' if not specified
        return ['post'];
    }
}
