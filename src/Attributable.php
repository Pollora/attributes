<?php

declare(strict_types=1);

namespace Pollora\Attributes;

/**
 * Marker interface that allows a class to be interpreted for PHP attributes.
 *
 * Classes implementing this interface can be processed by the AttributeProcessor
 * to analyze and handle their attributes dynamically.
 */
interface Attributable {}

/**
 * Trait that provides hook functionality for Attributable classes.
 *
 * This trait implements the getHook method which can be used by classes
 * that implement the Attributable interface to specify when their attributes
 * should be processed.
 */
trait AttributableHookTrait
{
    /**
     * Specifies the WordPress hook on which to process attributes.
     *
     * If this method returns a string, the attribute processing will be deferred
     * until the specified hook is triggered.
     * If it returns null, attributes will be processed immediately during class resolution.
     *
     * @return string|null The WordPress hook name or null for immediate processing
     */
    public function getHook(): ?string
    {
        return null;
    }
}
