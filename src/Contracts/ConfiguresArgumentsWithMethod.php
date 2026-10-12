<?php

declare(strict_types=1);

namespace Pollora\Attributes\Contracts;

/**
 * A method attribute that contributes to the arguments a post type or a
 * taxonomy is registered with, using the method it decorates as a callback.
 *
 * The discovery builds the callback, an instance of the declaring class and
 * the method name, and the attribute says where it goes.
 */
interface ConfiguresArgumentsWithMethod
{
    /**
     * Return the registration arguments with this attribute's contribution.
     *
     * @param  array<string, mixed>  $arguments  The arguments gathered so far
     * @param  callable  $method  The decorated method, bound to an instance of its class
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments, callable $method): array;
}
