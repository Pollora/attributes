<?php

declare(strict_types=1);

namespace Pollora\Attributes\Role;

use Attribute;
use BackedEnum;

/**
 * Removes capabilities from a `#[Role]` (inherited ones) or a `#[ModifyRole]`.
 *
 * The capability is removed, never set to false: WordPress merges the roles of
 * a user, and an explicit denial would win or lose depending on their order.
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final readonly class Without
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
