<?php

declare(strict_types=1);

use Pollora\Attributes\PostType\PostTypeAttribute;
use Pollora\Attributes\Taxonomy\TaxonomyAttribute;

/**
 * Attributes are declarations: a plugin writes them without the framework, an
 * IDE or a static analyser reads them alone. What they do lives with the
 * feature that reads them, so nothing here may reach a service, a helper or
 * the container.
 *
 * The enumerations listed are part of the attributes' vocabulary and travel
 * with them. The two deprecated handle() bases stay in the framework.
 */
arch('attributes depend on nothing but their own vocabulary')
    ->expect('Pollora\Attributes')
    ->toOnlyUse([
        'Pollora\Attributes',
        \Pollora\Ajax\Domain\Model\AjaxAccess::class,
        \Pollora\Abilities\Domain\Model\Behaviour::class,
        \Pollora\BlockBinding\Domain\Enums\BindingFieldType::class,
        \Pollora\Meta\Domain\Enums\Control::class,
        \Pollora\Role\Domain\Enums\Access::class,
        \Pollora\Schedule\Every::class,
        \Pollora\Schedule\Interval::class,
    ])
    // Permission::allow() names WordPress's request and error types in its
    // signature only, so the attributes never load them.
    ->ignoring([PostTypeAttribute::class, TaxonomyAttribute::class, 'WP_REST_Request', 'WP_Error']);
