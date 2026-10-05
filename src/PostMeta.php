<?php

declare(strict_types=1);

namespace Pollora\Attributes;

use Attribute;

/**
 * Declares `#[Meta]` properties for post types the class does not declare: a
 * core post type, a plugin's (WooCommerce's `product`), or several at once.
 *
 *     #[PostMeta('product')]
 *     class ProductExtras
 *     {
 *         #[Meta(showInRest: true)]
 *         public ?string $warrantyNotice = null;
 *     }
 *
 * For a post type declared with `#[PostType]`, put `#[Meta]` on that class.
 *
 * @experimental The API may still change before it is declared stable.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class PostMeta
{
    /** @var list<string> */
    public array $postTypes;

    /**
     * @param  string|list<string>  $postTypes  The post type slugs the meta belong to
     */
    public function __construct(string|array $postTypes)
    {
        $this->postTypes = array_values((array) $postTypes);
    }
}
