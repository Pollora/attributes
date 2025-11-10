<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Domain\Contracts\PostTypeAttributeInterface;

/**
 * Attribute to set the priority a post type declaration.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Priority extends PostTypeAttribute
{
    /**
     * Constructor.
     *
     * @param  init  $priority  The post type priority declaration
     */
    public function __construct(
        public readonly int $priority = 5
    ) {}

    /**
     * Configure the post type priority declaration parameter.
     *
     * @param  PostTypeAttributeInterface  $postType  The post type to configure
     */
    protected function configure(PostTypeAttributeInterface $postType): void
    {
        $postType->attributeArgs['priority'] = $this->priority;
    }
}
