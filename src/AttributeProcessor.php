<?php
namespace Pollora\Attributes;

use ReflectionClass;
use ReflectionMethod;
use Pollora\Attributes\Attributable;

class AttributeProcessor
{
    /**
     * Cache of mappings between attributes and their handlers.
     * @var array<string, string|null>
     */
    private static array $handlerCache = [];

    /**
     * Analyzes the attributes of a class and delegates their processing to the appropriate handlers.
     *
     * @param Attributable $instance The instance of the class using attributes
     * @return void
     */
    public static function process(Attributable $instance): void
    {
        $class = new ReflectionClass($instance);

        // Process attributes at the CLASS level
        foreach ($class->getAttributes() as $attribute) {
            self::processAttribute($instance, $attribute, $class);
        }

        // Process attributes at the METHOD level
        foreach ($class->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            foreach ($method->getAttributes() as $attribute) {
                self::processAttribute($instance, $attribute, $method);
            }
        }
    }

    /**
     * Process an attribute and call the appropriate handler.
     *
     * @param object $instance The class instance where the attribute is found
     * @param \ReflectionAttribute $attribute The attribute to process
     * @param ReflectionMethod|ReflectionClass|null $classOrMethod (Optional) The method if the attribute is on a method
     * @return void
     */
    private static function processAttribute(object $instance, \ReflectionAttribute $attribute, ReflectionMethod|ReflectionClass|null $classOrMethod = null): void
    {
        $attributeInstance = $attribute->newInstance();

        // Optimized retrieval of the handler
        $handlerClass = self::resolveHandlerClass($attributeInstance);

        if ($handlerClass !== null) {
            $handlerClass::handle($instance, $classOrMethod, $attributeInstance);
        }
    }

    /**
     * Dynamically generates the expected handler class name for an attribute with caching.
     *
     * @param object $attributeInstance Instance of the analyzed attribute
     * @return string|null Expected handler class name or null if it does not exist
     */
    private static function resolveHandlerClass(object $attributeInstance): ?string
    {
        $attributeClass = str_replace('Pollora\\Attributes\\', '', (new \ReflectionClass($attributeInstance))->getName());

        // Check if the class is already in the cache
        if (!isset(self::$handlerCache[$attributeClass])) {
            $resolvedClass = "Pollora\\Attributes\\Registrars\\{$attributeClass}Registrar";

            // Store if the class exists, otherwise null
            self::$handlerCache[$attributeClass] = class_exists($resolvedClass) ? $resolvedClass : null;
        }

        return self::$handlerCache[$attributeClass];
    }
}
