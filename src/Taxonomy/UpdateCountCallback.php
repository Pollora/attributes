<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Attributes\HandlesAttributes;
use Pollora\Taxonomy\Contracts\Taxonomy;
use ReflectionClass;
use ReflectionMethod;

/**
 * Attribute to define a callback function that will be called when the count is updated.
 *
 * This attribute can be applied to methods to define the callback
 * function that will be called when the count is updated.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_METHOD)]
class UpdateCountCallback implements HandlesAttributes
{
    /**
     * Handle the attribute processing.
     *
     * @param object $instance The instance being processed
     * @param ReflectionMethod|ReflectionClass $context The method the attribute is applied to
     * @param object $attribute The attribute instance
     */
    public function handle(object $instance, ReflectionMethod|ReflectionClass $context, object $attribute): void
    {
        if (! $instance instanceof Taxonomy) {
            return;
        }

        // Set the update_count_callback parameter to the method
        $instance->attributeArgs['update_count_callback'] = [$instance, $context->getName()];
    }
}
