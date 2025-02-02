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
