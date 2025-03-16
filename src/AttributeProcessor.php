<?php

declare(strict_types=1);

namespace Pollora\Attributes;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use SplObjectStorage;

/**
 * Class AttributeProcessor
 *
 * This class is responsible for processing PHP 8 attributes on classes and methods.
 * It provides caching and optimization mechanisms to improve performance in attribute handling.
 */
class AttributeProcessor
{
    /**
     * Stores processed classes to prevent redundant processing.
     * Uses SplObjectStorage to avoid memory leaks caused by circular references.
     *
     * @var SplObjectStorage<ReflectionClass, mixed>|null
     */
    private static ?SplObjectStorage $processedClasses = null;

    /**
     * Caches attributes for each class to optimize repeated processing.
     *
     * @var array<string, array{class: ReflectionAttribute[], methods: array<int, array{ReflectionMethod, ReflectionAttribute[]}>}>
     */
    private static array $attributeCache = [];

    /**
     * Caches attribute handlers to avoid repeated `method_exists` calls.
     *
     * @var array<class-string, callable|null>
     */
    private static array $handlersCache = [];

    /**
     * Debug mode for logging attribute processing.
     *
     * @var bool
     */
    private const DEBUG = false;

    /**
     * Processes all attributes on a given class instance and its methods.
     * Uses caching to avoid redundant processing and improve performance.
     *
     * @param  Attributable  $instance  The instance whose attributes should be processed.
     *
     * @throws AttributeProcessingException If an error occurs during processing.
     */
    public static function process(Attributable $instance): void
    {
        try {
            // Initialize the processed classes cache if not already done
            if (! self::$processedClasses instanceof \SplObjectStorage) {
                self::$processedClasses = new SplObjectStorage;
            }

            $class = new ReflectionClass($instance);

            // Skip processing if the class has already been processed
            if (self::$processedClasses->contains($class)) {
                return;
            }

            $className = $class->getName();

            // Cache attributes if not already cached
            if (! isset(self::$attributeCache[$className])) {
                self::$attributeCache[$className] = self::extractAttributes($class);
            }

            $attributes = self::$attributeCache[$className];

            // Process class-level attributes
            foreach ($attributes['class'] as $attribute) {
                self::processAttribute($instance, $attribute, $class);
            }

            // Process method-level attributes
            foreach ($attributes['methods'] as [$method, $methodAttributes]) {
                foreach ($methodAttributes as $attribute) {
                    self::processAttribute($instance, $attribute, $method);
                }
            }

            // Mark the class as processed
            self::$processedClasses->attach($class);

        } catch (\Throwable $e) {
            throw new AttributeProcessingException(
                sprintf('Failed to process attributes for class %s: %s', $instance::class, $e->getMessage()),
                0,
                $e
            );
        }
    }

    /**
     * Extracts all attributes from a class and its methods.
     *
     * @param  ReflectionClass  $class  The reflection class to extract attributes from.
     * @return array{class: ReflectionAttribute[], methods: array<int, array{ReflectionMethod, ReflectionAttribute[]}>}
     */
    private static function extractAttributes(ReflectionClass $class): array
    {
        $classAttributes = $class->getAttributes();

        $methodsWithAttributes = [];
        foreach ($class->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            $attributes = $method->getAttributes();
            // Only include methods that have attributes
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
     * Instantiates the attribute and invokes its handler if available.
     *
     * @param  object  $instance  The instance to which the attribute applies.
     * @param  ReflectionAttribute  $attribute  The reflection attribute instance.
     * @param  ReflectionClass|ReflectionMethod  $context  The context in which the attribute is applied (class or method).
     *
     * @throws AttributeProcessingException If an error occurs while processing the attribute.
     */
    private static function processAttribute(object $instance, ReflectionAttribute $attribute, ReflectionClass|ReflectionMethod $context): void
    {
        try {
            $attributeInstance = $attribute->newInstance();
            $handleMethod = self::resolveHandleMethod($attributeInstance);

            if (self::DEBUG) {
                error_log(
                    sprintf(
                        'Processing attribute %s on %s',
                        $attribute->getName(),
                        $context instanceof ReflectionMethod ? "method {$context->getName()}" : 'class'
                    )
                );
            }

            // Invoke the handler method if available
            if ($handleMethod !== null) {
                $handleMethod($instance, $context, $attributeInstance);
            }
        } catch (\Throwable $e) {
            throw new AttributeProcessingException(
                sprintf(
                    'Error processing attribute %s on %s: %s',
                    $attribute->getName(),
                    $context instanceof ReflectionMethod ? "method {$context->getName()}" : 'class',
                    $e->getMessage()
                ),
                0,
                $e
            );
        }
    }

    /**
     * Resolves and caches the handler method for an attribute.
     *
     * @param  object  $attributeInstance  The attribute instance.
     * @return callable|null Returns the callable handler method if found, or null otherwise.
     */
    private static function resolveHandleMethod(object $attributeInstance): ?callable
    {
        $attributeClass = $attributeInstance::class;

        // Check cache first
        if (array_key_exists($attributeClass, self::$handlersCache)) {
            return self::$handlersCache[$attributeClass];
        }

        // Resolve and cache the handler
        if ($attributeInstance instanceof HandlesAttributes) {
            return self::$handlersCache[$attributeClass] = $attributeInstance->handle(...);
        }

        // Cache null for non-handler attributes
        return self::$handlersCache[$attributeClass] = null;
    }

    /**
     * Clears the attribute cache and processed classes storage.
     * Useful for testing and development environments.
     */
    public static function clearCache(): void
    {
        self::$attributeCache = [];
        self::$handlersCache = [];
        self::$processedClasses = new SplObjectStorage;
    }

    /**
     * Checks if an attribute is repeatable based on its configuration.
     *
     * @param  ReflectionAttribute  $attribute  The attribute to check.
     * @return bool True if the attribute is repeatable, false otherwise.
     *
     * @throws ReflectionException
     */
    private static function isRepeatableAttribute(ReflectionAttribute $attribute): bool
    {
        $reflectionClass = new ReflectionClass($attribute->getName());
        $repeatable = $reflectionClass->getAttributes('Attribute');

        if ($repeatable === []) {
            return false;
        }

        return $repeatable[0]->newInstance()->isRepeatable();
    }

    /**
     * Gets the debug mode status.
     *
     * @return bool True if debug mode is enabled, false otherwise.
     */
    public static function isDebugMode(): bool
    {
        return self::DEBUG;
    }
}
