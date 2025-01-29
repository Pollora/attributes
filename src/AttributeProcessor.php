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
        $reflection = new ReflectionClass($instance);

        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            foreach ($method->getAttributes() as $attribute) {
                $attributeInstance = $attribute->newInstance();

                // Optimized retrieval of the handler
                $handlerClass = self::resolveHandlerClass($attributeInstance);

                if ($handlerClass !== null) {
                    $handlerClass::handle($instance, $method, $attributeInstance);
                }
            }
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
        $attributeClass = (new ReflectionClass($attributeInstance))->getShortName();

        // Check if the class is already in the cache
        if (!isset(self::$handlerCache[$attributeClass])) {
            $resolvedClass = "Pollora\\Attributes\\Registrars\\{$attributeClass}Registrar";

            // Store if the class exists, otherwise null
            self::$handlerCache[$attributeClass] = class_exists($resolvedClass) ? $resolvedClass : null;
        }

        return self::$handlerCache[$attributeClass];
    }
}
