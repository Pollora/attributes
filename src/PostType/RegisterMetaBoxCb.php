<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\Attributes\Contracts\HandlesAttributes;
use Pollora\PostType\Contracts\PostType;
use ReflectionClass;
use ReflectionMethod;

/**
 * Attribute to define a callback function that will be called when setting up meta boxes for the edit form.
 *
 * This attribute can be applied to methods to define the callback
 * function that will be called when setting up meta boxes for the edit form.
 * The callback function takes one argument $post, which contains
 * the WP_Post object for the currently edited post.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_METHOD)]
class RegisterMetaBoxCb implements HandlesAttributes
{
    /**
     * Handle the attribute processing.
     *
     * @param  object  $instance  The instance being processed
     * @param  ReflectionMethod|ReflectionClass  $context  The method the attribute is applied to
     * @param  object  $attribute  The attribute instance
     */
    public function handle($container, object $instance, ReflectionMethod|ReflectionClass $context, object $attribute): void
    {
        if (! $instance instanceof PostType) {
            return;
        }

        // Set the register_meta_box_cb parameter to the method
        $instance->attributeArgs['register_meta_box_cb'] = [$instance, $context->getName()];
    }
}
