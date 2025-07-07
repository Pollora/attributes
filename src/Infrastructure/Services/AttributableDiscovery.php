<?php

declare(strict_types=1);

namespace Pollora\Attributes\Infrastructure\Services;

use Pollora\Attributes\Contracts\Attributable;
use Pollora\Discovery\Domain\Contracts\DiscoveryInterface;
use Pollora\Discovery\Domain\Contracts\DiscoveryLocationInterface;
use Pollora\Discovery\Domain\Services\IsDiscovery;
use Spatie\StructureDiscoverer\Data\DiscoveredStructure;

/**
 * Attributable Discovery
 *
 * Discovers classes that implement the Attributable interface and collects them
 * for attribute processing. This discovery class scans for classes that implement
 * the Attributable interface and processes them through the attribute system.
 */
final class AttributableDiscovery implements DiscoveryInterface
{
    use IsDiscovery;

    /**
     * {@inheritDoc}
     *
     * Discovers classes implementing the Attributable interface and collects them for processing.
     * Only processes classes that implement the Attributable interface and are instantiable.
     */
    public function discover(DiscoveryLocationInterface $location, DiscoveredStructure $structure): void
    {
        // Only process classes
        if (! $structure instanceof \Spatie\StructureDiscoverer\Data\DiscoveredClass) {
            return;
        }

        // Skip abstract classes and interfaces
        if ($structure->isAbstract) {
            return;
        }

        // Check if class implements Attributable interface
        if (in_array(Attributable::class, $structure->implements ?? [])) {
            $this->getItems()->add($location, [
                'class' => $structure->namespace.'\\'.$structure->name,
                'structure' => $structure,
            ]);
        }
    }

    /**
     * {@inheritDoc}
     *
     * This discovery doesn't automatically apply discovered items since
     * AttributableServiceProvider handles the registration manually.
     * This method is kept empty as the processing is done elsewhere.
     */
    public function apply(): void
    {
        // This discovery is used only for collection,
        // the actual processing is handled by AttributableServiceProvider
    }

    /**
     * {@inheritDoc}
     */
    public function getIdentifier(): string
    {
        return 'attributable';
    }
}
