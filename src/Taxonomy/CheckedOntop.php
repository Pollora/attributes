<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set whether checked terms should appear on top.
 *
 * This allows you to override WordPress' default behaviour if necessary.
 * Default false if you're using a custom meta box, default true otherwise.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class CheckedOntop implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether checked terms should appear on top
     */
    public function __construct(
        public readonly bool $value = true
    ) {}

    /**
     * Configure the taxonomy with the checked_ontop parameter.
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['checked_ontop'] = $this->value;

        return $arguments;
    }
}
