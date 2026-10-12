<?php

declare(strict_types=1);

namespace Pollora\Attributes\Contracts;

/**
 * An attribute that contributes to the arguments a post type or a taxonomy is
 * registered with.
 *
 * The contribution is a pure function of the attribute's own values: the
 * discovery hands it the arguments gathered so far and registers what it
 * returns, so reading the attribute alone tells what it changes.
 */
interface ConfiguresArguments
{
    /**
     * Return the registration arguments with this attribute's contribution.
     *
     * @param  array<string, mixed>  $arguments  The arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array;
}
