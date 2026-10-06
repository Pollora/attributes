<?php

declare(strict_types=1);

namespace Pollora\Attributes;

use Attribute;

/**
 * Declares a Block Bindings source: a block attribute bound to it (the content
 * of a paragraph, the URL of a button) takes its value from the class.
 *
 *     #[BlockBinding('acme/event', label: 'Event')]
 *     final class EventBinding
 *     {
 *         #[BindingField(label: 'Remaining seats')]
 *         public function remainingSeats(BindingContext $context): string { … }
 *     }
 *
 * Each public method marked `#[BindingField]` is a field, chosen in the block
 * with the `field` argument. A class with no field defines `__invoke()` and
 * receives every call. The class is resolved by the container.
 *
 * @experimental The API may still change before it is declared stable.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class BlockBinding
{
    /** @var list<string> */
    public array $usesContext;

    /** @var list<string> */
    public array $postTypes;

    /**
     * @param  string  $name  Source name, `namespace/name` in lowercase, as WordPress requires
     * @param  string|null  $label  Label shown in the editor. Defaults to the class name, headlined
     * @param  list<string>  $usesContext  Block context the source reads
     * @param  string|list<string>  $postTypes  Post types the source answers for; empty for all
     */
    public function __construct(
        public string $name,
        public ?string $label = null,
        array $usesContext = ['postId', 'postType'],
        string|array $postTypes = [],
    ) {
        $this->usesContext = array_values($usesContext);
        $this->postTypes = array_values((array) $postTypes);
    }
}
