<?php

declare(strict_types=1);

namespace Pollora\Attributes;

use Attribute;
use Illuminate\Support\Str;

/**
 * Meta Attribute
 *
 * Marks a public typed property of a `#[PostType]`, `#[Taxonomy]`, `#[PostMeta]`,
 * `#[TermMeta]`, `#[UserMeta]` or `#[CommentMeta]` class as a
 * WordPress meta. The property type gives the meta type, its initial value the
 * default and its name the key:
 *
 *     #[PostType('event')]
 *     class Event
 *     {
 *         #[Meta(showInRest: true)]
 *         public int $capacity = 0;
 *     }
 *
 * The MetaDiscovery registers it with `register_meta()`, and `Meta::of()` reads
 * and writes it with its PHP type.
 *
 * @experimental The API may still change before it is declared stable.
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class Meta
{
    /**
     * @param  string|null  $key  Key stored in the database. Defaults to the property name in snake_case
     * @param  bool  $showInRest  Exposes the meta in the REST API, with a schema derived from its type
     * @param  string|null  $label  Human-readable label, shown by the editor
     * @param  string|null  $description  Description passed to `register_meta()`
     * @param  string|array{0: class-string|object, 1: string}|null  $sanitize  Callable replacing the sanitization derived from the type
     * @param  string|null  $capability  Capability required to write the meta through REST and the editor
     * @param  bool  $revisions  Versions the meta with post revisions (post types only)
     * @param  array<int, mixed>  $rules  Laravel validation rules, checked on writes from PHP and REST
     * @param  bool  $single  On an `array` property, false stores one row per item instead of one serialized array
     * @param  string|null  $items  On an `array` property, the item type: `'string'`, `'int'`, `'float'`, `'bool'` or a class. Defaults to the `@var list<…>` docblock
     */
    public function __construct(
        public ?string $key = null,
        public bool $showInRest = false,
        public ?string $label = null,
        public ?string $description = null,
        public string|array|null $sanitize = null,
        public ?string $capability = null,
        public bool $revisions = false,
        public array $rules = [],
        public bool $single = true,
        public ?string $items = null,
    ) {}

    /**
     * The key the meta is stored under for the given property.
     */
    public function resolveKey(string $property): string
    {
        return $this->key ?? Str::snake($property);
    }
}
