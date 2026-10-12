<?php

declare(strict_types=1);

namespace Pollora\Attributes\WpRestRoute;

use Attribute;
use InvalidArgumentException;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Method
{
    private const array ALLOWED_METHODS = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'];

    /**
     * Constructor for the Method attribute.
     *
     * @param  array|string  $methods  The HTTP methods allowed for this route.
     * @param  class-string<Permission>|Permission|null  $permissionCallback  The permission for this method, replacing the route's: a Permission class, or an instance such as `new Can('edit_posts')`.
     *
     * @throws InvalidArgumentException If an invalid HTTP method is provided.
     */
    public function __construct(
        public array|string $methods,
        public string|Permission|null $permissionCallback = null
    ) {
        $this->methods = is_array($methods) ? $methods : [$methods];
        $this->validateMethods();
    }

    /**
     * Validates the provided HTTP methods.
     *
     * @throws InvalidArgumentException If an invalid HTTP method is found.
     */
    private function validateMethods(): void
    {
        foreach ($this->methods as $method) {
            if (! in_array(strtoupper((string) $method), self::ALLOWED_METHODS, true)) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Invalid HTTP method "%s". Allowed methods are: %s',
                        $method,
                        implode(', ', self::ALLOWED_METHODS)
                    )
                );
            }
        }
    }

    /**
     * Retrieves the list of HTTP methods.
     *
     * @return array The list of allowed HTTP methods.
     */
    public function getMethods(): array
    {
        return $this->methods;
    }
}
