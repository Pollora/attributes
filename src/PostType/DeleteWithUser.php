<?php

declare(strict_types=1);

namespace Pollora\Attributes\PostType;

use Attribute;
use Pollora\Attributes\Contracts\ConfiguresArguments;

/**
 * Attribute to set the delete_with_user parameter for a post type.
 *
 * When set to true, posts of this type belonging to a user will be moved to trash
 * when the user is deleted.
 * When set to false, posts will be kept when the user is deleted.
 *
 * @Attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
class DeleteWithUser implements ConfiguresArguments
{
    /**
     * Constructor.
     *
     * @param  bool  $value  Whether posts should be deleted with their author
     */
    public function __construct(
        public readonly bool $value = true
    ) {}

    /**
     * Configure the post type with the delete_with_user parameter.
     *
     * @param  array<string, mixed>  $arguments  The registration arguments gathered so far
     * @return array<string, mixed>
     */
    public function configureArguments(array $arguments): array
    {
        $arguments['delete_with_user'] = $this->value;

        return $arguments;
    }
}
