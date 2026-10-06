<?php

declare(strict_types=1);

namespace Pollora\Meta\Domain\Enums;

/**
 * The kind of input a meta asks for, in terms no plugin owns. A UI driver
 * (ACF, Meta Box, an editor panel) maps it to its own fields.
 */
enum Control: string
{
    case Text = 'text';
    case Textarea = 'textarea';
    case RichText = 'rich-text';
    case Number = 'number';
    case Toggle = 'toggle';
    case Date = 'date';
    case DateTime = 'date-time';
    case Select = 'select';
    case Media = 'media';
    case Url = 'url';
    case Email = 'email';
    case Color = 'color';
}
