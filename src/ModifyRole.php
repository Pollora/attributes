<?php

declare(strict_types=1);

namespace Pollora\Attributes;

use Attribute;

/**
 * ModifyRole Attribute
 *
 * Grants or removes capabilities on a role the project does not own — a core
 * role, WooCommerce's `shop_manager`, a plugin's role — without redefining it:
 *
 *     #[ModifyRole('editor')]
 *     #[GrantsPostType(Event::class, Access::Editor)]
 *     #[Without('edit_theme_options')]
 *     final class EditorAdjustments {}
 *
 * Several classes may modify the same role, as long as they do not contradict
 * each other.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class ModifyRole
{
    /**
     * @param  string  $slug  The slug of the role to modify
     * @param  bool  $allowSensitive  Required to grant a sensitive capability (manage_options, edit_users…)
     */
    public function __construct(
        public string $slug,
        public bool $allowSensitive = false,
    ) {}
}
