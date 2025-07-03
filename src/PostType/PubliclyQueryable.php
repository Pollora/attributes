<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Domain\Contracts\PostTypeAttributeInterface;

/**
 * Attribute to set the publicly_queryable parameter for a post type.
 *
 * When set to true, the post type will be queryable via the front end.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class PubliclyQueryable extends PostTypeAttribute
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether the post type should be publicly queryable
     */
    public function __construct(
        public readonly bool $value = true
    ) {}

    /**
     * Configure the post type with the publicly_queryable parameter.
     *
     * @param  PostTypeAttributeInterface  $postType  The post type to configure
     */
    protected function configure(PostTypeAttributeInterface $postType): void
    {
        $postType->attributeArgs['publicly_queryable'] = $this->value;
    }
}
