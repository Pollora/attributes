<?php

declare(strict_types=1);

namespace Pollora\Attributes\Services;

use Pollora\Attributes\Contracts\Attributable;
use Pollora\Attributes\Contracts\AttributeContextInterface;
use Pollora\Attributes\Exceptions\AttributeProcessingException;
use ReflectionClass;

/**
 * Central attribute processor with domain isolation.
 *
 * This class orchestrates the complete attribute processing pipeline:
 * - Discovery and resolution of attributes
 * - Validation of domain compatibility
 * - Orchestrated processing with domain isolation
 * - Context management for attribute data
 */
class AttributeProcessor
{
    /**
     * Create a new AttributeProcessor instance.
     *
     * @param  AttributeRegistry  $registry  The attribute registry
     * @param  AttributeResolver  $resolver  The attribute resolver
     * @param  AttributeValidator  $validator  The attribute validator
     * @param  AttributeOrchestrator  $orchestrator  The attribute orchestrator
     */
    public function __construct(AttributeRegistry $registry, private readonly AttributeResolver $resolver, private readonly AttributeValidator $validator, private readonly AttributeOrchestrator $orchestrator) {}

    /**
     * Processes all attributes of a class with domain isolation.
     *
     * @param  string  $className  The class name to process
     * @param  mixed  $container  Optional dependency injection container
     * @return AttributeContextInterface The processing context with isolated domain data
     *
     * @throws AttributeProcessingException If processing fails
     */
    public function processClass(string $className, mixed $container = null): AttributeContextInterface
    {
        $reflection = new ReflectionClass($className);
        $instance = $this->createInstance($className);

        // Create isolated context
        $context = new AttributeContext($instance, $reflection);

        // Resolve attributes grouped by domain
        $attributesByDomain = $this->resolver->resolveAttributesByDomain($reflection);

        // Validate domain compatibility
        $this->validator->validateDomainCompatibility($instance, $attributesByDomain);

        // Process attributes by domain with orchestration
        $this->orchestrator->processByDomain($container, $context, $attributesByDomain);

        return $context;
    }

    /**
     * Check if a class has attributes that can be processed.
     *
     * @param  string  $className  The class name to check
     * @return bool True if the class has processable attributes
     */
    public function hasProcessableAttributes(string $className): bool
    {
        try {
            $reflection = new ReflectionClass($className);

            // Check for class-level attributes
            if ($reflection->getAttributes() !== []) {
                return true;
            }

            // Check for method-level attributes
            foreach ($reflection->getMethods() as $method) {
                if (! empty($method->getAttributes())) {
                    return true;
                }
            }

            return false;
        } catch (\Throwable) {
            return false;
        }
    }

    /**
     * Processes only attributes for a specific domain.
     *
     * @param  string  $className  The class name to process
     * @param  string  $domain  The specific domain to process
     * @param  mixed  $container  Optional dependency injection container
     * @return AttributeContextInterface The processing context with domain data
     *
     * @throws AttributeProcessingException If processing fails
     */
    public function processClassForDomain(
        string $className,
        string $domain,
        mixed $container = null
    ): AttributeContextInterface {
        $reflection = new ReflectionClass($className);
        $instance = $this->createInstance($className);

        // Verify instance supports the domain
        if (! $instance->supportsDomain($domain)) {
            throw new AttributeProcessingException(
                "Class {$className} does not support domain {$domain}"
            );
        }

        $context = new AttributeContext($instance, $reflection);

        // Resolve only for the specific domain
        $attributesByDomain = $this->resolver->resolveAttributesByDomain($reflection, [$domain]);

        if ($attributesByDomain === []) {
            return $context;
        }

        // Process only the specified domain
        $this->orchestrator->processByDomain($container, $context, $attributesByDomain);

        return $context;
    }

    /**
     * Creates an instance of the specified class.
     *
     * @param  string  $className  The class name to instantiate
     * @return object The created instance
     *
     * @throws AttributeProcessingException If the class cannot be instantiated
     */
    private function createInstance(string $className): object
    {
        try {
            $instance = new $className;

            // If the class implements Attributable, return it as is
            if ($instance instanceof Attributable) {
                return $instance;
            }

            // Otherwise, wrap it in an auto-attributable wrapper
            return new AutoAttributable($instance);
        } catch (\Throwable $e) {
            throw new AttributeProcessingException(
                "Failed to create instance of class {$className}: {$e->getMessage()}",
                0,
                $e
            );
        }
    }
}
