<?php

declare(strict_types=1);

namespace Pollora\Attributes\Services;

/**
 * Registry for attribute handlers and domain configuration.
 *
 * This class manages the registration of attribute handlers,
 * domain definitions, and compatibility rules between domains.
 */
class AttributeRegistry
{
    /**
     * Registered attribute handlers.
     *
     * @var array<class-string, class-string>
     */
    private array $handlers = [];

    /**
     * Registered domains with their configuration.
     *
     * @var array<string, array>
     */
    private array $domains = [];

    /**
     * Domain compatibility matrix.
     *
     * @var array<string, array<string, bool>>
     */
    private array $domainCompatibility = [];

    /**
     * Registers an attribute handler.
     *
     * @param  class-string  $attributeClass  The attribute class name
     * @param  class-string  $handlerClass  The handler class name
     */
    public function registerHandler(string $attributeClass, string $handlerClass): void
    {
        $this->handlers[$attributeClass] = $handlerClass;
    }

    /**
     * Registers a domain with its configuration.
     *
     * @param  string  $domain  The domain name
     * @param  array  $config  The domain configuration
     */
    public function registerDomain(string $domain, array $config): void
    {
        $this->domains[$domain] = $config;
    }

    /**
     * Sets compatibility between two domains.
     *
     * @param  string  $domain1  First domain name
     * @param  string  $domain2  Second domain name
     * @param  bool  $compatible  Whether the domains are compatible
     */
    public function setDomainCompatibility(string $domain1, string $domain2, bool $compatible = true): void
    {
        $this->domainCompatibility[$domain1][$domain2] = $compatible;
        $this->domainCompatibility[$domain2][$domain1] = $compatible;
    }

    /**
     * Checks if two domains are compatible.
     *
     * @param  string  $domain1  First domain name
     * @param  string  $domain2  Second domain name
     * @return bool True if compatible, false otherwise
     */
    public function areDomainsCompatible(string $domain1, string $domain2): bool
    {
        // A domain is always compatible with itself
        if ($domain1 === $domain2) {
            return true;
        }

        return $this->domainCompatibility[$domain1][$domain2] ?? false;
    }

    /**
     * Returns the handler for an attribute class.
     *
     * @param  class-string  $attributeClass  The attribute class name
     * @return class-string|null The handler class name or null if not found
     */
    public function getHandler(string $attributeClass): ?string
    {
        return $this->handlers[$attributeClass] ?? null;
    }

    /**
     * Returns the supported domains.
     *
     * @return array<string> List of domain names
     */
    public function getSupportedDomains(): array
    {
        return array_keys($this->domains);
    }

    /**
     * Returns the configuration for a domain.
     *
     * @param  string  $domain  The domain name
     * @return array The domain configuration
     */
    public function getDomainConfig(string $domain): array
    {
        return $this->domains[$domain] ?? [];
    }
}
