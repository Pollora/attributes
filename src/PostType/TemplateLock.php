<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

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
class TemplateLock implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  bool|string  $value  The template lock setting
     */
    public function __construct(
        public readonly bool|string $value
    ) {}

    /**
     * Configure the post type with the template_lock parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['template_lock'] = $this->value;

        return $arguments;
    }
}
