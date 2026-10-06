<?php

declare(strict_types=1);

namespace Pollora\Attributes\BlockBinding;

use Attribute;
use Pollora\BlockBinding\Domain\Enums\BindingFieldType;

/**
 * Marks a public method of a `#[BlockBinding]` class as a field a block can be
 * bound to, with `"args": {"field": "remaining_seats"}`.
 *
 * The method receives a `BindingContext` and any dependency the container can
 * resolve, and returns a `string`, `int`, `float`, `bool`, `Stringable` or
 * `null`; `null` keeps what the block holds.
 *
 * @experimental The API may still change before it is declared stable.
 */
#[Attribute(Attribute::TARGET_METHOD)]
final readonly class BindingField
{
    /**
     * @param  string|null  $name  Value of the `field` argument. Defaults to the method name in snake_case
     * @param  string|null  $label  Label shown in the editor. Defaults to the method name, headlined
     * @param  BindingFieldType|string  $type  `text`, `url` or `image`: a `url` or `image` value is sanitized as a URL
     */
    public function __construct(
        public ?string $name = null,
        public ?string $label = null,
        public BindingFieldType|string $type = BindingFieldType::Text,
    ) {}
}
