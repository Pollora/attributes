<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Contracts\PostType;

/**
 * Attribute to set the template_lock parameter for a post type.
 *
 * Whether the block template should be locked when the post is edited in the block editor.
 * - 'all': Prevents all operations (moving, removing, inserting blocks)
 * - 'insert': Prevents inserting or removing blocks, but allows moving existing ones
 * - false: Doesn't lock the template
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class TemplateLock extends PostTypeAttribute
{
    /**
     * Constructor.
     *
     * @param bool|string $value The template lock setting
     */
    public function __construct(
        private bool|string $value
    ) {
    }

    /**
     * Configure the post type with the template_lock parameter.
     *
     * @param PostType $postType The post type to configure
     *
     * @return void
     */
    protected function configure(PostType $postType): void
    {
        $postType->attributeArgs['template_lock'] = $this->value;
    }
} 