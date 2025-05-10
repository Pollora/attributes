<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Attributes\Contracts\HandlesAttributes;
use Pollora\Taxonomy\Contracts\Taxonomy;
use ReflectionClass;
use ReflectionMethod;

/**
 * Attribute to define a callback function for sanitizing taxonomy data saved from a meta box.
 *
 * This attribute can be applied to methods to define the callback
 * function for sanitizing taxonomy data saved from a meta box.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_METHOD)]
class MetaBoxSanitizeCb implements HandlesAttributes
{
    /**
     * Handle the attribute processing.
     *
     * @param object $instance The instance being processed
     * @param ReflectionMethod|ReflectionClass $context The method the attribute is applied to
     * @param object $attribute The attribute instance
     */
    public function handle($container, object $instance, ReflectionMethod|ReflectionClass $context, object $attribute): void
    {
        if (! $instance instanceof Taxonomy) {
            return;
        }

        // Set the meta_box_sanitize_cb parameter to the method
        $instance->attributeArgs['meta_box_sanitize_cb'] = [$instance, $context->getName()];
    }
}
