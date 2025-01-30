<?php
namespace Pollora\Attributes\WpRestRoute;

use Attribute;
use InvalidArgumentException;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Method
{
    private const ALLOWED_METHODS = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'];

    /**
     * Constructor for the Method attribute.
     *
     * @param array|string $methods The HTTP methods allowed for this route.
     * @param string|null $permissionCallback The callback function to check permissions for the route.
     * @throws InvalidArgumentException If an invalid HTTP method is provided.
     */
    public function __construct(
        public array|string $methods,
        public ?string $permissionCallback = null
    ) {
        $this->methods = is_array($methods) ? $methods : [$methods];
        $this->validateMethods();
    }

    /**
     * Validates the provided HTTP methods.
     *
     * @throws InvalidArgumentException If an invalid HTTP method is found.
     * @return void
     */
    private function validateMethods(): void
    {
        foreach ($this->methods as $method) {
            if (!in_array(strtoupper($method), self::ALLOWED_METHODS)) {
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
