<?php

declare(strict_types=1);

namespace Pollora\Attributes\Contracts;

/**
 * Interface for attributes that are typed to specific domains.
 *
 * This interface allows attributes to declare which domains they
 * support, enabling validation and proper attribute handling.
 */
interface TypedAttribute
{
    /**
     * Returns the domains supported by this attribute.
     *
     * @return array<string> List of supported domain names
     */
    public function getSupportedDomains(): array;

    /**
     * Verifies if this attribute supports a specific domain.
     *
     * @param  string  $domain  The domain name to check
     * @return bool True if the domain is supported, false otherwise
     */
    public function supportsDomain(string $domain): bool;
}
