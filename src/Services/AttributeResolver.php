<?php

declare(strict_types=1);

namespace Pollora\Attributes\Services;

use Pollora\Attributes\Contracts\HandlesAttributes;
use ReflectionClass;

/**
 * Service for resolving and grouping attributes by domain.
 *
 * This class handles the extraction and organization of attributes
 * from classes and methods, grouping them by their respective domains
 * and sorting them by priority.
 */
class AttributeResolver
{
    /**
     * Resolves attributes grouped by domain.
     *
     * @param  ReflectionClass  $reflection  The class reflection
     * @param  array<string>  $limitToDomains  Optional domain filter
     * @return array<string, array> Attributes grouped by domain
     */
    public function resolveAttributesByDomain(
        ReflectionClass $reflection,
        array $limitToDomains = []
    ): array {
        $attributesByDomain = [];

        // Resolve class-level attributes
        $classAttributes = $this->resolveClassAttributes($reflection);
        $this->groupAttributesByDomain($classAttributes, $attributesByDomain, $limitToDomains);

        // Resolve method-level attributes
        $methodAttributes = $this->resolveMethodAttributes($reflection);
        foreach ($methodAttributes as $attributes) {
            $this->groupAttributesByDomain($attributes, $attributesByDomain, $limitToDomains);
        }

        // Sort by priority within each domain
        foreach ($attributesByDomain as &$attributes) {
            usort($attributes, fn ($a, $b): int => $a['priority'] <=> $b['priority']);
        }

        return $attributesByDomain;
    }

    /**
     * Resolves class-level attributes.
     *
     * @param  ReflectionClass  $reflection  The class reflection
     * @return array<array> List of attribute data
     */
    private function resolveClassAttributes(ReflectionClass $reflection): array
    {
        $attributes = [];

        foreach ($reflection->getAttributes() as $attribute) {
            $instance = $attribute->newInstance();

            $attributes[] = [
                'instance' => $instance,
                'context' => $reflection,
                'priority' => $this->getAttributePriority($instance),
                'domain' => $this->getAttributeDomain($instance),
            ];
        }

        return $attributes;
    }

    /**
     * Resolves method-level attributes.
     *
     * @param  ReflectionClass  $reflection  The class reflection
     * @return array<string, array> Method attributes indexed by method name
     */
    private function resolveMethodAttributes(ReflectionClass $reflection): array
    {
        $methodAttributes = [];

        foreach ($reflection->getMethods() as $method) {
            $attributes = [];

            foreach ($method->getAttributes() as $attribute) {
                $instance = $attribute->newInstance();

                $attributes[] = [
                    'instance' => $instance,
                    'context' => $method,
                    'priority' => $this->getAttributePriority($instance),
                    'domain' => $this->getAttributeDomain($instance),
                ];
            }

            if ($attributes !== []) {
                $methodAttributes[$method->getName()] = $attributes;
            }
        }

        return $methodAttributes;
    }

    /**
     * Groups attributes by their respective domains.
     *
     * @param  array  $attributes  List of attribute data
     * @param  array  $attributesByDomain  Reference to the grouped attributes array
     * @param  array<string>  $limitToDomains  Optional domain filter
     */
    private function groupAttributesByDomain(
        array $attributes,
        array &$attributesByDomain,
        array $limitToDomains = []
    ): void {
        foreach ($attributes as $attributeData) {
            $domain = $attributeData['domain'];

            // Apply domain filter if specified
            if ($limitToDomains !== [] && ! in_array($domain, $limitToDomains)) {
                continue;
            }

            if (! isset($attributesByDomain[$domain])) {
                $attributesByDomain[$domain] = [];
            }

            $attributesByDomain[$domain][] = $attributeData;
        }
    }

    /**
     * Gets the priority of an attribute.
     *
     * @param  object  $attribute  The attribute instance
     * @return int The priority value (default: 100)
     */
    private function getAttributePriority(object $attribute): int
    {
        return $attribute instanceof HandlesAttributes
            ? $attribute->getPriority()
            : 100; // Default priority
    }

    /**
     * Gets the domain of an attribute.
     *
     * @param  object  $attribute  The attribute instance
     * @return string The domain name (default: 'unknown')
     */
    private function getAttributeDomain(object $attribute): string
    {
        return $attribute instanceof HandlesAttributes
            ? $attribute->getDomain()
            : 'unknown'; // Default domain
    }
}
