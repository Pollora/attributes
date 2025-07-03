<?php

declare(strict_types=1);

namespace Pollora\Attributes\Exceptions;

/**
 * Exception thrown when attribute validation fails.
 *
 * This exception is thrown when domain compatibility validation
 * fails or when attributes are not properly configured.
 */
class AttributeValidationException extends AttributeProcessingException {}
