<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArgumentsWithMethod;

/**
 * Attribute to define an admin column for a post type.
 *
 * This attribute can be applied to methods to define admin columns
 * in the WordPress admin list table for a post type.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_METHOD)]
class AdminCol implements ConfiguresArgumentsWithMethod
{
    /**
     * Constructor.
     *
     * @param  string  $key  The column key
     * @param  string  $title  The column title
     * @param  bool|string  $sortable  Enable sorting (true/false) or specify sortable field
     * @param  string|null  $titleIcon  Dashicon for column header
     * @param  string|null  $dateFormat  Date format for date-based fields
     * @param  string|null  $link  Link behavior: 'view', 'edit', 'list', or 'none'
     * @param  string|null  $cap  User capability required to view column
     * @param  string|null  $postCap  Capability required to view column content
     * @param  string|null  $default  Set as default sorting column ('ASC' or 'DESC')
     * @param  string|null  $metaKey  For meta field columns
     * @param  string|null  $taxonomy  For taxonomy columns
     * @param  string|null  $featuredImage  Featured image size
     * @param  string|null  $postField  Standard post table field
     * @param  int|null  $width  Column width in pixels
     */
    public function __construct(
        public readonly string $key,
        public readonly string $title,
        public readonly bool|string $sortable = false,
        public readonly ?string $titleIcon = null,
        public readonly ?string $dateFormat = null,
        public readonly ?string $link = null,
        public readonly ?string $cap = null,
        public readonly ?string $postCap = null,
        public readonly ?string $default = null,
        public readonly ?string $metaKey = null,
        public readonly ?string $taxonomy = null,
        public readonly ?string $featuredImage = null,
        public readonly ?string $postField = null,
        public readonly ?int $width = null
    ) {}

    /**
     * Add this column to the admin_cols argument, rendered by the decorated method.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @param  callable  $method  The decorated method
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments, callable $method): array
    {
        // Build column configuration based on extended-cpts options
        $columnConfig = [
            'title' => $this->title,
            'function' => $method,
        ];

        // Add optional configuration based on extended-cpts features
        if ($this->titleIcon !== null) {
            $columnConfig['title_icon'] = $this->titleIcon;
        }

        if ($this->dateFormat !== null) {
            $columnConfig['date_format'] = $this->dateFormat;
        }

        if ($this->link !== null) {
            $columnConfig['link'] = $this->link;
        }

        if ($this->cap !== null) {
            $columnConfig['cap'] = $this->cap;
        }

        if ($this->postCap !== null) {
            $columnConfig['post_cap'] = $this->postCap;
        }

        if ($this->default !== null) {
            $columnConfig['default'] = $this->default;
        }

        if ($this->metaKey !== null) {
            $columnConfig['meta_key'] = $this->metaKey;
        }

        if ($this->taxonomy !== null) {
            $columnConfig['taxonomy'] = $this->taxonomy;
        }

        if ($this->featuredImage !== null) {
            $columnConfig['featured_image'] = $this->featuredImage;
        }

        if ($this->postField !== null) {
            $columnConfig['post_field'] = $this->postField;
        }

        if ($this->width !== null) {
            $columnConfig['width'] = $this->width;
        }

        // Handle sortable configuration
        if ($this->sortable !== false) {
            $columnConfig['sortable'] = $this->sortable;
        }

        $arguments['admin_cols'] = [...$arguments['admin_cols'] ?? [], $this->key => $columnConfig];

        return $arguments;
    }
}
