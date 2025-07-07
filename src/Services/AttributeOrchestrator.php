<?php

declare(strict_types=1);

namespace Pollora\Attributes\Services;

use Pollora\Attributes\Contracts\AttributeContextInterface;
use Pollora\Attributes\Contracts\HandlesAttributes;

/**
 * Service for orchestrating attribute processing with domain isolation.
 *
 * This class manages the execution of attribute handlers,
 * ensuring proper isolation between domains and ordered processing
 * based on attribute priorities.
 */
class AttributeOrchestrator
{
    /**
     * Processes attributes grouped by domain.
     *
     * @param  mixed  $container  The dependency injection container
     * @param  AttributeContextInterface  $context  The attribute context
     * @param  array<string, array>  $attributesByDomain  Attributes grouped by domain
     */
    public function processByDomain(
        mixed $container,
        AttributeContextInterface $context,
        array $attributesByDomain
    ): void {
        foreach ($attributesByDomain as $attributes) {
            $this->processDomain($container, $context, $attributes);
        }
    }

    /**
     * Processes attributes for a specific domain.
     *
     * @param  mixed  $container  The dependency injection container
     * @param  AttributeContextInterface  $context  The attribute context
     * @param  array  $attributes  List of attributes for the domain
     */
    private function processDomain(
        mixed $container,
        AttributeContextInterface $context,
        array $attributes
    ): void {
        foreach ($attributes as $attributeData) {
            $this->processAttribute(
                $container,
                $context,
                $attributeData['context'],
                $attributeData['instance']
            );
        }
    }

    /**
     * Processes an individual attribute.
     *
     * @param  mixed  $container  The dependency injection container
     * @param  AttributeContextInterface  $context  The attribute context
     * @param  mixed  $reflection  The reflection context (class or method)
     * @param  object  $attribute  The attribute instance
     */
    private function processAttribute(
        mixed $container,
        AttributeContextInterface $context,
        \ReflectionClass|\ReflectionMethod $reflection,
        object $attribute
    ): void {
        if ($attribute instanceof HandlesAttributes) {
            $attribute->handle($container, $context, $reflection, $attribute);
        }
    }
}
