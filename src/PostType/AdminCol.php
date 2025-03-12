<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\Attributes\HandlesAttributes;
use Pollora\PostType\Contracts\PostType;
use ReflectionClass;
use ReflectionMethod;

/**
 * Attribute to define an admin column for a post type.
 *
 * This attribute can be applied to methods to define admin columns
 * in the WordPress admin list table for a post type.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_METHOD)]
class AdminCol implements HandlesAttributes
{
    /**
     * Constructor.
     *
     * @param  string  $key  The column key
     * @param  string  $title  The column title
     */
    public function __construct(
        private string $key,
        private string $title
    ) {}

    /**
     * Handle the attribute processing.
     *
     * @param  object  $instance  The instance being processed
     * @param  ReflectionMethod  $context  The method the attribute is applied to
     * @param  self  $attribute  The attribute instance
     */
    public function handle(object $instance, ReflectionMethod|ReflectionClass $context, object $attribute): void
    {
        if (! $instance instanceof PostType) {
            return;
        }

        // Initialize admin_cols array if it doesn't exist
        if (! isset($instance->attributeArgs['admin_cols'])) {
            $instance->attributeArgs['admin_cols'] = [];
        }

        // Create the column configuration
        $columnConfig = [
            'title' => $this->title,
            'function' => [$instance, $context->getName()],
        ];

        // Add the column to the admin_cols array
        $instance->attributeArgs['admin_cols'][$this->key] = $columnConfig;
    }
}
