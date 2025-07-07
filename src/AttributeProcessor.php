<?php

declare(strict_types=1);

namespace Pollora\Attributes;

use Pollora\Attributes\Exceptions\AttributeProcessingException;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;
use SplObjectStorage;

/**
 * Class AttributeProcessor
 *
 * This class is responsible for processing PHP 8 attributes on classes and methods with intelligent caching.
 * It provides optimized performance by caching reflection data while ensuring that each instance gets
 * fresh attribute instances to avoid value pollution between different objects.
 *
 * The processor implements a three-tier caching strategy:
 * 1. Instance-level processing tracking to prevent duplicate processing
 * 2. Class-level attribute extraction caching (ReflectionAttribute objects)
 * 3. Attribute handler method caching for performance optimization
 *
 * Key Features:
 * - Prevents attribute value pollution between different class instances
 * - Optimized performance through intelligent caching mechanisms
 * - Support for deferred processing via WordPress hooks
 * - Memory-efficient using SplObjectStorage to prevent memory leaks
 * - Thread-safe caching implementation
 *
 * @author  Pollora Team
 *
 * @since   1.0.0
 */
class AttributeProcessor
{
    /**
     * Storage for processed instances to prevent duplicate processing.
     *
     * Uses SplObjectStorage to track which specific object instances have already
     * been processed. This prevents the same instance from being processed multiple
     * times while allowing different instances of the same class to be processed
     * independently.
     *
     * @var SplObjectStorage<object, mixed>|null
     */
    private static ?SplObjectStorage $processedInstances = null;

    /**
     * Cache for extracted attributes by class name.
     *
     * Stores the ReflectionAttribute objects extracted from classes to avoid
     * expensive reflection operations on repeated processing. The cache key
     * is the fully qualified class name, and the value contains both class-level
     * and method-level attributes.
     *
     * Structure:
     * ```
     * [
     *     'ClassName' => [
     *         'class' => ReflectionAttribute[],
     *         'methods' => [
     *             [ReflectionMethod, ReflectionAttribute[]],
     *             ...
     *         ]
     *     ]
     * ]
     * ```
     *
     * @var array<string, array{class: ReflectionAttribute[], methods: array<int, array{ReflectionMethod, ReflectionAttribute[]}>}>
     */
    private static array $extractedAttributesCache = [];

    /**
     * Cache for attribute handler methods by attribute class name.
     *
     * Caches the result of checking whether an attribute class has a 'handle' method
     * to avoid repeated `method_exists` calls. The cache key is the attribute class
     * name, and the value is a boolean indicating method existence.
     *
     * @var array<class-string, bool>
     */
    private static array $handleMethodsCache = [];

    /**
     * Dependency injection container.
     *
     * Optional container that can be used for dependency injection when
     * instantiating attributes or passing to attribute handlers.
     *
     * @var mixed
     */
    private $container;

    /**
     * Constructor.
     *
     * Initializes the AttributeProcessor with an optional dependency injection container.
     *
     * @param  mixed  $container  Optional dependency injection container
     */
    public function __construct($container = null)
    {
        $this->container = $container;
    }

    public function process(object $instance): void
    {
        try {
            // Initialize processed instances storage if not already done
            if (! self::$processedInstances instanceof \SplObjectStorage) {
                self::$processedInstances = new \SplObjectStorage;
            }

            // Check if this specific instance has already been processed
            if (self::$processedInstances->contains($instance)) {
                return;
            }

            $class = new \ReflectionClass($instance);

            // Check if the instance specifies a hook for deferred processing
            $hook = null;
            if (method_exists($instance, 'getHook')) {
                $hook = $instance->getHook();
            }

            // If a hook is specified, defer processing to that hook
            if ($hook !== null) {
                add_action($hook, function () use ($instance, $class) {
                    $this->processInstance($instance, $class);
                }, 10);

                // Mark this instance as processed to prevent duplicate hook registration
                self::$processedInstances->attach($instance);

                return;
            }

            // Process immediately if no hook is specified
            $this->processInstance($instance, $class);

            // Mark this instance as processed
            self::$processedInstances->attach($instance);
        } catch (\Throwable $e) {
            throw new AttributeProcessingException(
                sprintf('Failed to process attributes for class %s: %s', $instance::class, $e->getMessage()),
                0,
                $e
            );
        }
    }

    /**
     * Processes the attributes for a given instance and reflection class.
     *
     * This method handles the actual attribute processing logic:
     * - Extracts attributes from the class (with caching)
     * - Processes class-level attributes
     * - Processes method-level attributes
     *
     * Each attribute gets a fresh instance to prevent value pollution between
     * different object instances.
     *
     * @param  object  $instance  The object instance being processed
     * @param  ReflectionClass  $class  The reflection class of the instance
     *
     * @throws AttributeProcessingException If an error occurs during processing
     *
     * @since 1.0.0
     */
    private function processInstance(object $instance, \ReflectionClass $class): void
    {
        try {
            // Use caching for extracted attributes (they don't change per class)
            $className = $class->getName();

            if (! isset(self::$extractedAttributesCache[$className])) {
                self::$extractedAttributesCache[$className] = $this->extractAttributes($class);
            }

            $attributes = self::$extractedAttributesCache[$className];

            // Process class-level attributes - fresh instance for each processing
            foreach ($attributes['class'] as $attribute) {
                $this->processAttribute($instance, $attribute, $class);
            }

            // Process method-level attributes - fresh instance for each processing
            foreach ($attributes['methods'] as [$method, $methodAttributes]) {
                foreach ($methodAttributes as $attribute) {
                    $this->processAttribute($instance, $attribute, $method);
                }
            }
        } catch (\Throwable $e) {
            throw new AttributeProcessingException(
                sprintf('Failed to process attributes for class %s: %s', $instance::class, $e->getMessage()),
                0,
                $e
            );
        }
    }

    /**
     * Extracts all attributes from a class and its public methods.
     *
     * This method performs reflection to gather all attributes defined on:
     * - The class itself
     * - All public methods of the class
     *
     * The results are cached to avoid repeated reflection operations for the same class.
     *
     * @param  ReflectionClass  $class  The reflection class to extract attributes from
     * @return array{class: ReflectionAttribute[], methods: array<int, array{ReflectionMethod, ReflectionAttribute[]}>}
     *                                                                                                                  An array containing class and method attributes
     *
     * @since 1.0.0
     */
    private function extractAttributes(\ReflectionClass $class): array
    {
        $classAttributes = $class->getAttributes();

        $methodsWithAttributes = [];
        foreach ($class->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            $attributes = $method->getAttributes();
            if (! empty($attributes)) {
                $methodsWithAttributes[] = [$method, $attributes];
            }
        }

        return [
            'class' => $classAttributes,
            'methods' => $methodsWithAttributes,
        ];
    }

    /**
     * Processes an individual attribute.
     *
     * This method handles the processing of a single attribute:
     * - Creates a fresh instance of the attribute class
     * - Resolves the handler method (with caching)
     * - Invokes the handler if available
     *
     * Each call creates a new attribute instance to ensure that different object
     * instances don't share attribute state, preventing value pollution.
     *
     * @param  object  $instance  The object instance being processed
     * @param  ReflectionAttribute  $attribute  The reflection attribute to process
     * @param  ReflectionClass|ReflectionMethod  $context  The context (class or method) where the attribute is defined
     *
     * @throws AttributeProcessingException If an error occurs while processing the attribute
     *
     * @since 1.0.0
     */
    private function processAttribute(object $instance, \ReflectionAttribute $attribute, \ReflectionClass|\ReflectionMethod $context): void
    {
        try {
            // Always create a fresh attribute instance to prevent value pollution
            $attributeInstance = $this->instantiateAttribute($attribute);
            $handleMethod = $this->resolveHandleMethod($attributeInstance);

            if ($handleMethod !== null) {
                $handleMethod($this->container, $instance, $context, $attributeInstance);
            }
        } catch (\Throwable $e) {
            throw new AttributeProcessingException(
                sprintf(
                    'Error processing attribute %s on %s: %s',
                    $attribute->getName(),
                    $context instanceof \ReflectionMethod ? "method {$context->getName()}" : 'class',
                    $e->getMessage()
                ),
                0,
                $e
            );
        }
    }

    /**
     * Instantiates a fresh attribute instance.
     *
     * This method creates a new instance of the attribute class every time it's called,
     * ensuring that each processing gets a fresh instance with its own state.
     * This is crucial to prevent attribute value pollution between different object instances.
     *
     * The method manually creates instances using the class name and arguments to bypass
     * PHP's built-in attribute instance caching mechanism.
     *
     * @param  ReflectionAttribute  $attribute  The reflection attribute to instantiate
     * @return object The instantiated attribute object
     *
     * @throws \RuntimeException If an error occurs during instantiation
     *
     * @since 1.0.0
     */
    private function instantiateAttribute(\ReflectionAttribute $attribute): object
    {
        try {
            // Force a new instance every time - NO CACHING HERE
            $attributeClass = $attribute->getName();
            $arguments = $attribute->getArguments();

            return new $attributeClass(...$arguments);
        } catch (\Throwable $e) {
            throw new \RuntimeException('Error instantiating attribute: '.$e->getMessage(), 0, $e);
        }
    }

    /**
     * Resolves and caches the handler method for an attribute.
     *
     * This method determines if an attribute class has a 'handle' method and caches
     * the result to avoid repeated `method_exists` calls. The caching is safe because
     * it's based on the attribute class, not the instance.
     *
     * @param  object  $attributeInstance  The attribute instance to check for a handler
     * @return callable|null Returns the callable handler method if found, null otherwise
     *
     * @since 1.0.0
     */
    private function resolveHandleMethod(object $attributeInstance): ?callable
    {
        $attributeClass = $attributeInstance::class;

        // Cache handler method existence (safe because it's based on class, not instance)
        if (array_key_exists($attributeClass, self::$handleMethodsCache)) {
            $cachedMethod = self::$handleMethodsCache[$attributeClass];

            return $cachedMethod ? $attributeInstance->handle(...) : null;
        }

        if (method_exists($attributeInstance, 'handle')) {
            self::$handleMethodsCache[$attributeClass] = true;

            return $attributeInstance->handle(...);
        }

        self::$handleMethodsCache[$attributeClass] = false;

        return null;
    }

    /**
     * Clears all internal caches.
     *
     * This method provides a way to reset all caching mechanisms, which can be useful
     * for testing, debugging, or in long-running processes where memory management
     * is important.
     *
     * Clears:
     * - Processed instances tracking
     * - Extracted attributes cache
     * - Handler methods cache
     *
     * @since 1.0.0
     */
    public static function clearCaches(): void
    {
        self::$processedInstances = null;
        self::$extractedAttributesCache = [];
        self::$handleMethodsCache = [];
    }

    /**
     * Gets the current cache statistics for debugging and monitoring.
     *
     * Returns information about the current state of all caches, which can be
     * useful for performance monitoring and debugging.
     *
     * @return array{
     *     processed_instances_count: int,
     *     extracted_attributes_cache_count: int,
     *     handle_methods_cache_count: int
     * } Cache statistics
     *
     * @since 1.0.0
     */
    public static function getCacheStats(): array
    {
        return [
            'processed_instances_count' => self::$processedInstances ? self::$processedInstances->count() : 0,
            'extracted_attributes_cache_count' => count(self::$extractedAttributesCache),
            'handle_methods_cache_count' => count(self::$handleMethodsCache),
        ];
    }
}
