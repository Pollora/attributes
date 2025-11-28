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
 * Example:
 * ```php
 * #[WpCli('Hello command suite')]
 * class HelloCommand
 * {
 *     #[Command('Say hello to the world')] // slug auto-generated from method name
 *     public function world(array $arguments, array $options): void
 *     {
 *         WP_CLI::success('Hello, World!');
 *     }
 *
 *     #[Command('Say hello to a user', 'custom-name')] // custom slug
 *     public function user(array $arguments, array $options): void
 *     {
 *         $name = $arguments[0] ?? 'there';
 *         WP_CLI::success("Hello, {$name}!");
 *     }
 * }
 * ```
 *
 * This would create:
 * - wp hello world
 * - wp hello custom-name
 */
#[Attribute(Attribute::TARGET_METHOD)]
final class Command
{
    /**
     * Create a new WP CLI Command attribute.
     *
     * @param string $description The subcommand description
     * @param string|null $commandName Optional custom subcommand name/slug. If null, generated from method name.
     */
    public function __construct(
        public readonly string $description,
        public readonly ?string $commandName = null
    ) {}

    /**
     * Get the subcommand name, generating it from method name if not provided.
     *
     * @param string $methodName The method name
     * @return string The subcommand name/slug
     */
    public function getSubcommandName(string $methodName): string
    {
        return $this->commandName ?? Slug::fromMethodName($methodName);
    }
}