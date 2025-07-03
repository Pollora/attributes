<?php

declare(strict_types=1);

namespace Pollora\Attributes\Contracts;

/**
 * Interface for attributes that define compatibility rules with other domains.
 *
 * This interface enables a flexible compatibility system where attributes can:
 * - Be compatible with all domains by default (no restrictions)
 * - Define specific domains they're compatible with (inclusion)
 * - Define specific domains they're incompatible with (exclusion)
 */
interface AttributeCompatibility
{
    /**
     * Get domains that this attribute is explicitly compatible with.
     *
     * If this returns null or empty array, the attribute is considered
     * compatible with all domains (unless excluded via getIncompatibleDomains).
     *
     * @return array<string>|null List of compatible domain names, or null for all domains
     */
    public function getCompatibleDomains(): ?array;

    /**
     * Get domains that this attribute is explicitly incompatible with.
     *
     * These domains will be excluded from compatibility, even if they
     * would otherwise be compatible.
     *
     * @return array<string> List of incompatible domain names
     */
    public function getIncompatibleDomains(): array;

    /**
     * Check if this attribute is compatible with a specific domain.
     *
     * This method implements the compatibility logic:
     * 1. If domain is in incompatible list -> false
     * 2. If compatible list is empty/null -> true (compatible with all)
     * 3. If domain is in compatible list -> true
     * 4. Otherwise -> false
     *
     * @param  string  $domain  The domain to check compatibility with
     * @return bool True if compatible, false otherwise
     */
    public function isCompatibleWith(string $domain): bool;
}
