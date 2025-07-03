<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Domain\Contracts\PostTypeAttributeInterface;

/**
 * Attribute to set the rest_controller_class parameter for a post type.
 *
 * Sets the controller class name for the REST API.
 * By default, this is 'WP_REST_Posts_Controller'.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class RestControllerClass extends PostTypeAttribute
{
    /**
     * Constructor.
     *
     * @param  string  $value  The REST controller class for the post type
     */
    public function __construct(
        public readonly string $value
    ) {}

    /**
     * Configure the post type with the rest_controller_class parameter.
     *
     * @param  PostTypeAttributeInterface  $postType  The post type to configure
     */
    protected function configure(PostTypeAttributeInterface $postType): void
    {
        $postType->attributeArgs['rest_controller_class'] = $this->value;
    }
}
