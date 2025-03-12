<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\PostType\Contracts\PostType;

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
     * @param string $value The REST controller class for the post type
     */
    public function __construct(
        private string $value
    ) {
    }

    /**
     * Configure the post type with the rest_controller_class parameter.
     *
     * @param PostType $postType The post type to configure
     *
     * @return void
     */
    protected function configure(PostType $postType): void
    {
        $postType->attributeArgs['rest_controller_class'] = $this->value;
    }
} 