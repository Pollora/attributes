<?php

declare(strict_types=1);

namespace Pollora\Attributes\Taxonomy;

use Pollora\Attributes\Contracts\AttributeContextInterface;
use Pollora\Attributes\Contracts\HandlesAttributes;
use Pollora\Attributes\Contracts\TypedAttribute;
use ReflectionClass;
use ReflectionMethod;

/**
 * Abstract base class for Taxonomy sub-attributes.
 *
 * This class provides common functionality for all Taxonomy-related attributes,
 * eliminating code duplication and ensuring consistent behavior.
 */
abstract class AbstractTaxonomyAttribute implements HandlesAttributes, TypedAttribute
{
    /**
     * Handle the attribute processing.
     *
     * @param  mixed  $container  Dependency injection container
     * @param  AttributeContextInterface  $context  Attribute context with isolated domain data
     * @param  ReflectionClass|ReflectionMethod  $reflection  Reflection of the class/method
     * @param  object  $attribute  Instance of the attribute
     */
    final public function handle(
        mixed $container,
        AttributeContextInterface $context,
        ReflectionClass|ReflectionMethod $reflection,
        object $attribute
    ): void {
        // Validate target type if specified
        if ($this->getTargetType() === 'class' && ! $reflection instanceof ReflectionClass) {
            return;
        }

        if ($this->getTargetType() === 'method' && ! $reflection instanceof ReflectionMethod) {
            return;
        }

        // Call the specific configuration method
        $this->configure($context, $reflection, $attribute);
    }

    /**
     * Configure the taxonomy with this attribute's data.
     *
     * @param  AttributeContextInterface  $context  The attribute context
     * @param  ReflectionClass|ReflectionMethod  $reflection  The reflection object
     * @param  object  $attribute  The attribute instance
     */
    abstract protected function configure(
        AttributeContextInterface $context,
        ReflectionClass|ReflectionMethod $reflection,
        object $attribute
    ): void;

    /**
     * Get the target type for this attribute ('class', 'method', or null for both).
     *
     * @return string|null The target type
     */
    protected function getTargetType(): ?string
    {
        return null; // By default, allow both class and method targets
    }

    /**
     * {@inheritDoc}
     */
    public function getPriority(): int
    {
        return 20; // Process after the main Taxonomy attribute
    }

    /**
     * {@inheritDoc}
     */
    public function isCombinable(): bool
    {
        return true; // Most sub-attributes can be combined
    }

    /**
     * {@inheritDoc}
     */
    public function getDomain(): string
    {
        return 'taxonomy';
    }

    /**
     * {@inheritDoc}
     */
    public function isCompatibleWith(string $domain): bool
    {
        return $domain === 'taxonomy';
    }

    /**
     * {@inheritDoc}
     */
    public function getSupportedDomains(): array
    {
        return ['taxonomy'];
    }

    /**
     * {@inheritDoc}
     */
    public function supportsDomain(string $domain): bool
    {
        return $domain === 'taxonomy';
    }

    /**
     * Helper method to merge data into the taxonomy domain.
     *
     * @param  AttributeContextInterface  $context  The context
     * @param  array  $data  The data to merge
     */
    protected function mergeTaxonomyData(AttributeContextInterface $context, array $data): void
    {
        $context->mergeDataForDomain('taxonomy', $data);
    }

    /**
     * Helper method to get existing taxonomy domain data.
     *
     * @param  AttributeContextInterface  $context  The context
     * @return array The existing taxonomy data
     */
    protected function getTaxonomyData(AttributeContextInterface $context): array
    {
        return $context->getDataForDomain('taxonomy');
    }

    /**
     * Helper method to set taxonomy domain data.
     *
     * @param  AttributeContextInterface  $context  The context
     * @param  array  $data  The data to set
     */
    protected function setTaxonomyData(AttributeContextInterface $context, array $data): void
    {
        $context->setDataForDomain('taxonomy', $data);
    }
}
