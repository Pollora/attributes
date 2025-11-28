<?php

declare(strict_types=1);

namespace Pollora\Attributes;

use Pollora\Support\Slug;

/**
 * Attribute to mark a class as a WordPress CLI command.
 *
 * This attribute provides WordPress-specific command registration through WP CLI.
 * Classes marked with this attribute will be automatically discovered and registered with WP CLI.
 *
 * Usage:
 * ```php
 * #[WpCli('Description of my command')] // slug auto-generated from class name
 * class MyCommand
 * {
 *     public function __invoke(array $arguments, array $options): void
 *     {
 *         // Command implementation
 *     }
 * }
 * 
 * // Or with custom slug:
 * #[WpCli('Description of my command', 'custom-slug')]
 * class MyCommand { ... }
 * ```
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
final class WpCli
{
    /**
     * Create a new WP CLI attribute.
     *
     * @param string $description The command description
     * @param string|null $commandName Optional custom command name/slug. If null, generated from class name.
     */
    public function __construct(
        public readonly string $description,
        public readonly ?string $commandName = null
    ) {}

    /**
     * Get the command name, generating it from class name if not provided.
     *
     * @param string $className The fully qualified class name
     * @return string The command name/slug
     */
    public function getCommandName(string $className): string
    {
        return $this->commandName ?? Slug::fromClassName($className);
    }
}