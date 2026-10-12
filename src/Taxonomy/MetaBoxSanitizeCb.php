<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArgumentsWithMethod;

/**
 * Attribute to define a callback function for sanitizing taxonomy data saved from a meta box.
 *
 * This attribute can be applied to methods to define the callback
 * function for sanitizing taxonomy data saved from a meta box.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_METHOD)]
class MetaBoxSanitizeCb implements ConfiguresArgumentsWithMethod
{
    /**
     * Set the meta_box_sanitize_cb argument to the decorated method.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @param  callable  $method  The decorated method
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments, callable $method): array
    {
        $arguments['meta_box_sanitize_cb'] = $method;

        return $arguments;
    }
}
