<?php

declare(strict_types=1);

namespace Pollora\Role\Domain\Enums;

/**
 * An access level on a post type, named after the core role that has it on posts.
 *
 * Each level includes the previous ones.
 */
enum Access: string
{
    /** Create and edit their own drafts, without publishing. */
    case Contributor = 'contributor';

    /** Publish and manage their own content. */
    case Author = 'author';

    /** Manage everyone's content, private content included. */
    case Editor = 'editor';

    /**
     * The generic post capabilities of the level, as `get_post_type_capabilities()` names them.
     *
     * @return list<string>
     */
    public function genericCapabilities(): array
    {
        $contributor = ['edit_posts', 'delete_posts'];
        $author = ['publish_posts', 'edit_published_posts', 'delete_published_posts'];
        $editor = ['edit_others_posts', 'delete_others_posts', 'read_private_posts', 'edit_private_posts', 'delete_private_posts'];

        return match ($this) {
            self::Contributor => $contributor,
            self::Author => [...$contributor, ...$author],
            self::Editor => [...$contributor, ...$author, ...$editor],
        };
    }
}
