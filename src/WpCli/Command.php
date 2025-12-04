<?php

declare(strict_types=1);

namespace Pollora\Attributes\WpCli;

use Attribute;
use Pollora\Support\Slug;

/**
 * WP CLI Command Attribute for Method-Level Subcommands
 *
 * This attribute is used to mark methods as WP CLI subcommands within a class
 * that has the main #[WpCli] attribute. It allows for multiple commands to be
 * defined within a single class, creating subcommands.
 *
 * The command description is automatically extracted from the method's PHPDoc comment.
 *
 * Usage:
 * - Use #[Command] for auto-generated subcommand slug from method name
 * - Use #[Command('custom-slug')] for custom subcommand slug
 * - Add PHPDoc comments to define subcommand description and help
 * - Declare methods as private to avoid automatic exposure by WP CLI
 */
#[Attribute(Attribute::TARGET_METHOD)]
final class Command
{
    /**
     * Create a new WP CLI Command attribute.
     *
     * @param  string|null  $commandName  Optional custom subcommand name/slug. If null, generated from method name.
     */
    public function __construct(
        public readonly ?string $commandName = null
    ) {}

    /**
     * Get the subcommand name, generating it from method name if not provided.
     *
     * @param  string  $methodName  The method name
     * @return string The subcommand name/slug
     */
    public function getSubcommandName(string $methodName): string
    {
        return $this->commandName ?? Slug::fromMethodName($methodName);
    }
}
