<?php

declare(strict_types=1);

namespace Pollora\Attributes;

use Attribute;
use Illuminate\Support\Str;

/**
 * PostType Attribute
 *
 * This attribute is used to mark classes as WordPress custom post types.
 * It contains only the essential data needed for post type definition.
 *
 * The actual registration and processing logic is handled by the PostTypeDiscovery
 * class in the discovery system, which scans for classes with this attribute and
 * processes all related sub-attributes to build the complete post type configuration.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class PostType
{
    /**
     * PostType attribute constructor.
     *
     * Creates a new PostType attribute instance that defines the basic parameters
     * for a WordPress custom post type.
     *
     * @param  string|null  $slug  The post type slug. If null, auto-generated from class name using kebab-case
     * @param  string|null  $singular  The singular name for the post type. If null, auto-generated from class name
     * @param  string|null  $plural  The plural name for the post type. If null, auto-pluralized from singular name
     * @param  string|null  $textDomain  The text domain for i18n. If null, defaults to 'pollora'
     */
    public function __construct(
        public readonly ?string $slug = null,
        public readonly ?string $singular = null,
        public readonly ?string $plural = null,
        public readonly ?string $textDomain = null
    ) {
        // No validation here - will be handled by PostTypeDiscovery
    }

    /**
     * The slug WordPress registers the post type under.
     *
     * The explicit slug when the attribute has one; otherwise the class name in
     * kebab-case, cut to 20 characters. Discovery registers with it and
     * `pollora:status` reports it, so the two cannot disagree.
     *
     * @param  string  $className  The class carrying the attribute
     */
    public function resolveSlug(string $className): string
    {
        return $this->slug ?? substr(Str::kebab(class_basename($className)), 0, 20);
    }
}
