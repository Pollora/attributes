<?php

declare(strict_types=1);

namespace Pollora\Attributes\Services;

use Pollora\Attributes\Contracts\Attributable;
use ReflectionClass;

/**
 * Automatic wrapper for classes that have attributes but don't implement Attributable.
 *
 * This class automatically detects which domains a class supports based on
 * the attributes present on the class, eliminating the need for manual
 * interface implementation.
 */
class AutoAttributable implements Attributable
{
    /**
     * The wrapped instance.
     */
    private object $instance;

    /**
     * Cached supported domains.
     *
     * @var array<string>|null
     */
    private ?array $cachedSupportedDomains = null;

    /**
     * Create a new AutoAttributable wrapper.
     *
     * @param  object  $instance  The instance to wrap
     */
    public function __construct(object $instance)
    {
        $this->instance = $instance;
    }

    /**
     * {@inheritDoc}
     */
    public function getSupportedDomains(): array
    {
        if ($this->cachedSupportedDomains === null) {
            $this->cachedSupportedDomains = $this->detectSupportedDomains();
        }

        return $this->cachedSupportedDomains;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsDomain(string $domain): bool
    {
        return in_array($domain, $this->getSupportedDomains());
    }

    /**
     * Get the original wrapped instance.
     *
     * @return object The original instance
     */
    public function getOriginalInstance(): object
    {
        return $this->instance;
    }

    /**
     * Detect which domains this class supports based on its attributes.
     *
     * @return array<string> List of supported domains
     */
    private function detectSupportedDomains(): array
    {
        $reflection = new ReflectionClass($this->instance);
        $supportedDomains = [];

        // Check class-level attributes
        $supportedDomains = array_merge(
            $supportedDomains,
            $this->extractDomainsFromAttributes($reflection->getAttributes())
        );

        // Check method-level attributes
        foreach ($reflection->getMethods() as $method) {
            $supportedDomains = array_merge(
                $supportedDomains,
                $this->extractDomainsFromAttributes($method->getAttributes())
            );
        }

        return array_unique($supportedDomains);
    }

    /**
     * Extract domain names from an array of reflection attributes.
     *
     * @param  array<\ReflectionAttribute>  $attributes  The attributes to analyze
     * @return array<string> The domains found
     */
    private function extractDomainsFromAttributes(array $attributes): array
    {
        $domains = [];

        foreach ($attributes as $attribute) {
            $attributeClass = $attribute->getName();

            // Try to instantiate the attribute to get its domain
            try {
                $attributeInstance = $attribute->newInstance();

                // Check if the attribute implements our domain-aware interfaces
                if (method_exists($attributeInstance, 'getDomain')) {
                    $domains[] = $attributeInstance->getDomain();
                } elseif (method_exists($attributeInstance, 'getSupportedDomains')) {
                    $domains = array_merge($domains, $attributeInstance->getSupportedDomains());
                } else {
                    // No domain methods found, try to infer from class name
                    $domains = array_merge($domains, $this->inferDomainFromAttributeClass($attributeClass));
                }
            } catch (\Throwable $e) {
                // If we can't instantiate the attribute, try to infer from class name
                $domains = array_merge($domains, $this->inferDomainFromAttributeClass($attributeClass));
            }
        }

        return $domains;
    }

    /**
     * Infer the domain from an attribute class name.
     *
     * @param  string  $attributeClass  The attribute class name
     * @return array<string> Inferred domains
     */
    private function inferDomainFromAttributeClass(string $attributeClass): array
    {
        // Map common attribute classes to their domains
        $domainMappings = [
            'Pollora\Attributes\PostType' => ['post_type'],
            'Pollora\Attributes\Taxonomy' => ['taxonomy'],
            'Pollora\Attributes\Action' => ['hook'],
            'Pollora\Attributes\Filter' => ['hook'],
            'Pollora\Attributes\Schedule' => ['hook'],
        ];

        // Check for PostType sub-attributes
        if (str_starts_with($attributeClass, 'Pollora\Attributes\PostType\\')) {
            return ['post_type'];
        }

        // Check for Taxonomy sub-attributes
        if (str_starts_with($attributeClass, 'Pollora\Attributes\Taxonomy\\')) {
            return ['taxonomy'];
        }

        return $domainMappings[$attributeClass] ?? [];
    }

    /**
     * Forward method calls to the original instance.
     *
     * @param  string  $method  The method name
     * @param  array  $arguments  The method arguments
     * @return mixed The method result
     */
    public function __call(string $method, array $arguments)
    {
        return $this->instance->$method(...$arguments);
    }

    /**
     * Forward property access to the original instance.
     *
     * @param  string  $property  The property name
     * @return mixed The property value
     */
    public function __get(string $property)
    {
        return $this->instance->$property;
    }

    /**
     * Forward property setting to the original instance.
     *
     * @param  string  $property  The property name
     * @param  mixed  $value  The property value
     */
    public function __set(string $property, $value): void
    {
        $this->instance->$property = $value;
    }

    /**
     * Forward property existence check to the original instance.
     *
     * @param  string  $property  The property name
     * @return bool True if the property exists
     */
    public function __isset(string $property): bool
    {
        return isset($this->instance->$property);
    }

    /**
     * Forward property unset to the original instance.
     *
     * @param  string  $property  The property name
     */
    public function __unset(string $property): void
    {
        unset($this->instance->$property);
    }
}
