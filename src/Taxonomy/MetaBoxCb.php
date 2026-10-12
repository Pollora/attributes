<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArgumentsWithMethod;

/**
 * Attribute to define a callback function for the meta box display.
 *
 * This attribute can be applied to methods to define the callback
 * function for the meta box display of a taxonomy.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_METHOD)]
class MetaBoxCb implements ConfiguresArgumentsWithMethod
{
    /**
     * Set the meta_box_cb argument to the decorated method.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @param  callable  $method  The decorated method
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments, callable $method): array
    {
        $arguments['meta_box_cb'] = $method;

        return $arguments;
    }
}
