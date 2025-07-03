<?php

declare(strict_types=1);

namespace Pollora\Attributes\Contracts;

/**
 * Interface for classes that can be processed by the attribute system.
 *
 * This simplified interface allows classes to declare which domains
 * they support, enabling the attribute processor to validate and
 * process attributes appropriately.
 */
interface Attributable
{
    /**
     * Returns the domains supported by this entity.
     *
     * @return array<string> List of supported domain names
     */
    public function getSupportedDomains(): array;

    /**
     * Indicates if this entity supports a specific domain.
     *
     * @param  string  $domain  The domain name to check
     * @return bool True if the domain is supported, false otherwise
     */
    public function supportsDomain(string $domain): bool;
}
