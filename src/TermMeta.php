<?php

declare(strict_types=1);

namespace Pollora\Attributes;

use Attribute;

/**
 * Declares `#[Meta]` properties for taxonomies the class does not declare: a
 * core taxonomy (`category`), a plugin's, or several at once.
 *
 *     #[TermMeta('category')]
 *     class CategoryExtras
 *     {
 *         #[Meta(showInRest: true)]
 *         public ?string $color = null;
 *     }
 *
 * For a taxonomy declared with `#[Taxonomy]`, put `#[Meta]` on that class.
 *
 * @experimental The API may still change before it is declared stable.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class TermMeta
{
    /** @var list<string> */
    public array $taxonomies;

    /**
     * @param  string|list<string>  $taxonomies  The taxonomy slugs the meta belong to
     */
    public function __construct(string|array $taxonomies)
    {
        $this->taxonomies = array_values((array) $taxonomies);
    }
}
