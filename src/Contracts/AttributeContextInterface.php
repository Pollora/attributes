<?php

declare(strict_types=1);

namespace Pollora\Attributes\Contracts;

use ReflectionClass;

/**
 * Interface for attribute context handling with domain isolation.
 *
 * This interface provides a structured way to handle attribute data
 * by isolating different domains (post_type, taxonomy, hook, etc.)
 * to prevent data conflicts and ensure proper attribute processing.
 */
interface AttributeContextInterface
{
    /**
     * Returns the data for a specific domain.
     *
     * @param  string  $domain  The domain name (e.g., 'post_type', 'taxonomy', 'hook')
     * @return array The domain data
     */
    public function getDataForDomain(string $domain): array;

    /**
     * Sets the data for a specific domain.
     *
     * @param  string  $domain  The domain name
     * @param  array  $data  The data to set for the domain
     */
    public function setDataForDomain(string $domain, array $data): void;

    /**
     * Merges data for a specific domain.
     *
     * @param  string  $domain  The domain name
     * @param  array  $data  The data to merge with existing domain data
     */
    public function mergeDataForDomain(string $domain, array $data): void;

    /**
     * Checks if a domain exists in the context.
     *
     * @param  string  $domain  The domain name to check
     * @return bool True if the domain exists, false otherwise
     */
    public function hasDomain(string $domain): bool;

    /**
     * Returns all domains present in the context.
     *
     * @return array<string> List of domain names
     */
    public function getDomains(): array;

    /**
     * Returns the original instance of the class.
     *
     * @return object The original instance
     */
    public function getOriginalInstance(): object;

    /**
     * Returns the reflection class of the original instance.
     *
     * @return ReflectionClass The reflection class
     */
    public function getClassReflection(): ReflectionClass;
}
