<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArgumentsWithMethod;

/**
 * Attribute to define a callback function that will be called when the count is updated.
 *
 * This attribute can be applied to methods to define the callback
 * function that will be called when the count is updated.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_METHOD)]
class UpdateCountCallback implements ConfiguresArgumentsWithMethod
{
    /**
     * Set the update_count_callback argument to the decorated method.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @param  callable  $method  The decorated method
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments, callable $method): array
    {
        $arguments['update_count_callback'] = $method;

        return $arguments;
    }
}
