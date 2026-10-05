<?php

declare(strict_types=1);

namespace Pollora\Attributes\Role;

use Attribute;
use BackedEnum;

/**
 * Grants capabilities to a `#[Role]` or a `#[ModifyRole]`, as strings or
 * backed enum cases.
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final readonly class Grants
{
    /**
     * @var list<string|BackedEnum>
     */
    public array $capabilities;

    public function __construct(string|BackedEnum ...$capabilities)
    {
        $this->capabilities = array_values($capabilities);
    }
}
