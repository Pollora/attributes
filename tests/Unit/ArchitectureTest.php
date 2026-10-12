<?php

declare(strict_types=1);

use Pollora\Abilities\Domain\Model\Behaviour;
use Pollora\Ajax\Domain\Model\AjaxAccess;
use Pollora\BlockBinding\Domain\Enums\BindingFieldType;
use Pollora\Meta\Domain\Enums\Control;
use Pollora\Role\Domain\Enums\Access;
use Pollora\Schedule\Every;
use Pollora\Schedule\Interval;

/**
 * Attributes are declarations: a plugin writes them without the framework, an
 * IDE or a static analyser reads them alone. What they do lives with the
 * feature that reads them, so nothing here may reach a service, a helper or
 * a container.
 *
 * The vocabulary is the enumerations and value objects the attributes take;
 * AjaxAccess and Behaviour come from pollora/ajax and pollora/abilities, which
 * depend on PHP alone.
 */
$vocabulary = [BindingFieldType::class, Control::class, Access::class, Every::class, Interval::class];

arch('attributes depend on nothing but their own vocabulary')
    ->expect('Pollora\Attributes')
    ->toOnlyUse(['Pollora\Attributes', AjaxAccess::class, Behaviour::class, ...$vocabulary])
    // Permission::allow() names WordPress's request and error types in its
    // signature only, so the attributes never load them.
    ->ignoring(['WP_REST_Request', 'WP_Error']);

arch('the vocabulary depends on nothing but itself')
    ->expect($vocabulary)
    ->toOnlyUse($vocabulary);
