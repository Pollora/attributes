<?php

declare(strict_types=1);

namespace Pollora\Attributes\Services;

use Pollora\Attributes\Contracts\AttributeContextInterface;
use ReflectionClass;

/**
 * Implementation of attribute context with domain isolation.
 *
 * This class provides isolated storage for attribute data by domain,
 * preventing conflicts between different types of attributes and
 * enabling clean separation of concerns.
 */
class AttributeContext implements AttributeContextInterface
{
    /**
     * Storage for domain-specific data.
     *
     * @var array<string, array>
     */
    private array $domainData = [];

    /**
     * Create a new AttributeContext instance.
     *
     * @param  object  $originalInstance  The original instance being processed
     * @param  ReflectionClass  $classReflection  The reflection class
     */
    public function __construct(private readonly object $originalInstance, private readonly ReflectionClass $classReflection) {}

    /**
     * {@inheritDoc}
     */
    public function getDataForDomain(string $domain): array
    {
        return $this->domainData[$domain] ?? [];
    }

    /**
     * {@inheritDoc}
     */
    public function setDataForDomain(string $domain, array $data): void
    {
        $this->domainData[$domain] = $data;
    }

    /**
     * {@inheritDoc}
     */
    public function mergeDataForDomain(string $domain, array $data): void
    {
        if (! isset($this->domainData[$domain])) {
            $this->domainData[$domain] = [];
        }

        $this->domainData[$domain] = array_merge_recursive(
            $this->domainData[$domain],
            $data
        );
    }

    /**
     * {@inheritDoc}
     */
    public function hasDomain(string $domain): bool
    {
        return isset($this->domainData[$domain]);
    }

    /**
     * {@inheritDoc}
     */
    public function getDomains(): array
    {
        return array_keys($this->domainData);
    }

    /**
     * {@inheritDoc}
     */
    public function getOriginalInstance(): object
    {
        return $this->originalInstance;
    }

    /**
     * {@inheritDoc}
     */
    public function getClassReflection(): ReflectionClass
    {
        return $this->classReflection;
    }
}
