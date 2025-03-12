<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Attributes\HandlesAttributes;
use Pollora\Taxonomy\Contracts\Taxonomy;
use ReflectionClass;
use ReflectionMethod;

/**
 * Attribute to define a callback function for the meta box display.
 *
 * This attribute can be applied to methods to define the callback
 * function for the meta box display of a taxonomy.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_METHOD)]
class MetaBoxCb implements HandlesAttributes
{
    /**
     * Handle the attribute processing.
     *
     * @param  object  $instance  The instance being processed
     * @param  ReflectionMethod  $context  The method the attribute is applied to
     * @param  self  $attribute  The attribute instance
     */
    public function handle(object $instance, ReflectionMethod|ReflectionClass $context, object $attribute): void
    {
        if (! $instance instanceof Taxonomy) {
            return;
        }

        // Set the meta_box_cb parameter to the method
        $instance->attributeArgs['meta_box_cb'] = [$instance, $context->getName()];
    }
} 