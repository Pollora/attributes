<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArgumentsWithMethod;

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
class RegisterMetaBoxCb implements ConfiguresArgumentsWithMethod
{
    /**
     * Set the register_meta_box_cb argument to the decorated method.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @param  callable  $method  The decorated method
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments, callable $method): array
    {
        $arguments['register_meta_box_cb'] = $method;

        return $arguments;
    }
}
