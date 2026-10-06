<?php

declare(strict_types=1);

namespace Pollora\BlockBinding\Domain\Enums;

/**
 * What a binding field gives, which decides how its value is escaped and, in
 * the editor, which attributes it is offered for.
 */
enum BindingFieldType: string
{
    case Text = 'text';
    case Url = 'url';
    case Image = 'image';

    /**
     * Whether the value is a URL, sanitized as one.
     */
    public function isUrl(): bool
    {
        return $this !== self::Text;
    }
}
