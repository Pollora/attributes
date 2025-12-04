<?php

declare(strict_types=1);

namespace Pollora\Attributes;

use Pollora\Support\Slug;

/**
 * Attribute to mark a class as a WordPress CLI command.
 *
 * This attribute provides WordPress-specific command registration through WP CLI.
 * Classes marked with this attribute will be automatically discovered and registered with WP CLI.
 * The command description is automatically extracted from the class's PHPDoc comment.
 *
 * Usage:
 * - Use #[WpCli] for auto-generated command slug from class name
 * - Use #[WpCli('custom-slug')] for custom command slug
 * - Add PHPDoc comments to define command description and help
 * - Command descriptions support WP CLI format with OPTIONS and EXAMPLES sections
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
final class WpCli
{
    /**
     * Create a new WP CLI attribute.
     *
     * @param  string|null  $commandName  Optional custom command name/slug. If null, generated from class name.
     */
    public function __construct(
        public readonly ?string $commandName = null
    ) {}

    /**
     * Get the command name, generating it from class name if not provided.
     *
     * @param  string  $className  The fully qualified class name
     * @return string The command name/slug
     */
    public function getCommandName(string $className): string
    {
        return $this->commandName ?? Slug::fromClassName($className);
    }
}
