<?php
declare(strict_types=1);

namespace Pollora\Attributes;

use ReflectionClass;
use ReflectionMethod;
use WeakMap;
use RuntimeException;

/**
 * Class AttributeProcessor
 *
 * Processes PHP 8 attributes on classes and methods, providing caching and optimization
 * for better performance in attribute handling.
 */
class AttributeProcessor
{
    /**
     * Cache of processed classes to avoid redundant processing
     * Using WeakMap to prevent memory leaks with circular references
     */
    private static ?WeakMap $processedClasses = null;

    /**
     * Cache of attribute handlers to avoid repeated method_exists calls
     * @var array<string, callable|null>
     */
    private static array $handlersCache = [];

    /**
     * Process all attributes on a class instance and its methods
     *
     * @param Attributable $instance The instance to process attributes for
     * @throws AttributeProcessingException If an error occurs during processing
     */
    public static function process(Attributable $instance): void
    {
        try {
            // Initialize WeakMap if not already done
            if (self::$processedClasses === null) {
                self::$processedClasses = new WeakMap();
            }

            $class = new ReflectionClass($instance);

            // Skip if already processed
            if (isset(self::$processedClasses[$instance])) {
                return;
            }

            self::processClassAttributes($instance, $class);
            self::processMethodAttributes($instance, $class);

            // Mark as processed
            self::$processedClasses[$instance] = true;

        } catch (\Throwable $e) {
            throw new AttributeProcessingException(
                "Failed to process attributes for class " . get_class($instance),
                0,
                $e
            );
        }
    }

    /**
     * Process class-level attributes
     *
     * @param object $instance
     * @param ReflectionClass $class
     * @throws AttributeProcessingException
     */
    private static function processClassAttributes(object $instance, ReflectionClass $class): void
    {
        $attributes = $class->getAttributes();
        if (empty($attributes)) {
            return;
        }

        foreach ($attributes as $attribute) {
            self::processAttribute($instance, $attribute, $class);
        }
    }

    /**
     * Process method-level attributes with optimized attribute loading
     *
     * @param object $instance
     * @param ReflectionClass $class
     * @throws AttributeProcessingException
     */
    private static function processMethodAttributes(object $instance, ReflectionClass $class): void
    {
        $methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);

        // Pre-load all method attributes to optimize processing
        $methodAttributes = [];
        foreach ($methods as $method) {
            $attributes = $method->getAttributes();
            if (!empty($attributes)) {
                $methodAttributes[$method->getName()] = [$method, $attributes];
            }
        }

        // Process the collected attributes
        foreach ($methodAttributes as [$method, $attributes]) {
            foreach ($attributes as $attribute) {
                self::processAttribute($instance, $attribute, $method);
            }
        }
    }

    /**
     * Process an individual attribute
     *
     * @param object $instance
     * @param \ReflectionAttribute $attribute
     * @param ReflectionMethod|ReflectionClass|null $classOrMethod
     * @throws AttributeProcessingException
     */
    private static function processAttribute(
        object $instance,
        \ReflectionAttribute $attribute,
        ReflectionMethod|ReflectionClass|null $classOrMethod = null
    ): void {
        try {
            $attributeInstance = $attribute->newInstance();
            $handleMethod = self::resolveHandleMethod($attributeInstance);

            if ($handleMethod !== null) {
                $handleMethod($instance, $classOrMethod, $attributeInstance);
            }
        } catch (\Throwable $e) {
            dd($e->getMessage());
            throw new AttributeProcessingException(
                sprintf(
                    "Error processing attribute %s on %s",
                    $attribute->getName(),
                    $classOrMethod instanceof ReflectionMethod ? "method {$classOrMethod->getName()}" : "class"
                ),
                0,
                $e
            );
        }
    }

    /**
     * Resolve and cache the handler method for an attribute
     *
     * @param object $attributeInstance
     * @return callable|null
     */
    private static function resolveHandleMethod(object $attributeInstance): ?callable
    {
        $attributeClass = get_class($attributeInstance);

        // Check cache first
        if (isset(self::$handlersCache[$attributeClass])) {
            return self::$handlersCache[$attributeClass];
        }

        // Resolve and cache handler
        $handler = method_exists($attributeClass, 'handle')
            ? [$attributeInstance, 'handle']
            : null;

        self::$handlersCache[$attributeClass] = $handler;

        return $handler;
    }
}
