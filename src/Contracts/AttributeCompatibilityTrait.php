<?php

declare(strict_types=1);

namespace Pollora\Attributes\Contracts;

/**
 * Trait providing default implementation for AttributeCompatibility interface.
 *
 * This trait implements the flexible compatibility system logic:
 * - Compatible with all domains by default
 * - Supports inclusion lists (only compatible with specific domains)
 * - Supports exclusion lists (incompatible with specific domains)
 */
trait AttributeCompatibilityTrait
{
    /**
     * {@inheritDoc}
     */
    public function getCompatibleDomains(): ?array
    {
        return null; // Compatible with all domains by default
    }

    /**
     * {@inheritDoc}
     */
    public function getIncompatibleDomains(): array
    {
        return []; // No incompatible domains by default
    }

    /**
     * {@inheritDoc}
     */
    public function isCompatibleWith(string $domain): bool
    {
        // First check if explicitly incompatible
        if (in_array($domain, $this->getIncompatibleDomains(), true)) {
            return false;
        }

        $compatibleDomains = $this->getCompatibleDomains();

        // If no specific compatible domains defined, compatible with all
        if ($compatibleDomains === null || empty($compatibleDomains)) {
            return true;
        }

        // Check if domain is in the compatible list
        return in_array($domain, $compatibleDomains, true);
    }
}
