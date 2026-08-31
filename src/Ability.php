<?php

declare(strict_types=1);

namespace Pollora\Attributes;

use Attribute;
use Pollora\Abilities\Domain\Contracts\AbilityHandler;
use Pollora\Abilities\Domain\Model\Behaviour;
use Pollora\Ability\Infrastructure\Services\AbilityDiscovery;

/**
 * Attribute for declarative WordPress ability registration.
 *
 * Place this attribute on a class implementing {@see AbilityHandler}. The class
 * is discovered and registered with the WordPress Abilities API through the
 * {@see AbilityDiscovery} system, and published to AI clients — an MCP server,
 * the core abilities REST controllers — by whatever consumes them.
 *
 * The attribute carries what the ability *is*; the handler carries what it
 * *does*. Nothing here needs repeating in the class body.
 *
 * Usage:
 *
 *     #[Ability(
 *         name: 'acme/create-post',
 *         description: 'Creates a draft post from a title and a body.',
 *         category: 'acme-content',
 *         behaviour: Behaviour::Creates,
 *     )]
 *     final class CreatePost implements AbilityHandler { … }
 *
 * The category must exist. Declare it once — in a service provider, through the
 * `Ability` facade — before the abilities that file under it.
 *
 * @see AbilityHandler  The interface the decorated class implements.
 * @see Behaviour       How the ability's effect on the site is described to clients.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Ability
{
    /**
     * @param  string  $name  Fully-qualified ability name, `namespace/slug`. Both parts are
     *                        lowercase alphanumerics separated by single dashes.
     * @param  string  $description  What the ability does, written for a model deciding whether
     *                               to call it. This is the tool description, and it is required:
     *                               an undescribed tool never gets used correctly.
     * @param  string  $category  Slug of the category the ability is filed under.
     * @param  string  $label  Short human-readable title. Defaults to a title-cased slug.
     * @param  Behaviour  $behaviour  What the ability does to the site. Defaults to reading, the
     *                                only shape that is safe for a client to run unattended.
     */
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly string $category,
        public readonly string $label = '',
        public readonly Behaviour $behaviour = Behaviour::Reads,
    ) {}
}
