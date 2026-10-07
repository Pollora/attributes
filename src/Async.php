<?php

declare(strict_types=1);

namespace Pollora\Attributes;

use Attribute;

/**
 * Makes #[Action] methods asynchronous: the handler is queued when its hook
 * fires, then run later (Laravel queue, Action Scheduler or WP-Cron).
 *
 * The counterpart of Action::add(...)->async(): #[Action] keeps the hook and
 * the priority, #[Async] carries the deferred-execution options.
 *
 *     #[Action('save_post_event')]
 *     #[Async(delay: 60, unique: true, tries: 3)]
 *     public function syncToCrm(int $postId): void {}
 *
 * On a class, it applies to every #[Action] method; an #[Async] on a method
 * replaces the class options for that method. With several #[Action] on a
 * method, it applies to all of them, except the hooks listed in $except.
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
final readonly class Async
{
    /**
     * @param  int|\DateInterval|null  $delay  Minimum delay before execution, in seconds
     * @param  string|null  $via  Driver: queue, action-scheduler, wp-cron, sync
     * @param  string|null  $onQueue  Queue name, the group with Action Scheduler
     * @param  bool|int  $unique  Merge identical triggers until the first one runs; an integer sets how long the lock lasts, in seconds
     * @param  int|null  $tries  Attempts when the handler throws
     * @param  int|list<int>|null  $backoff  Seconds before each retry
     * @param  bool|null  $asUser  Run as the user who fired the hook
     * @param  string|null  $capture  Public method of the class recording values at trigger time
     * @param  string|null  $when  Public method of the class deciding, at trigger time, whether to queue
     * @param  bool  $keepMissing  Run with null in place of a referenced object deleted in the meantime
     * @param  string|list<string>  $except  Hooks of the method that stay synchronous
     */
    public function __construct(
        public int|\DateInterval|null $delay = null,
        public ?string $via = null,
        public ?string $onQueue = null,
        public bool|int $unique = false,
        public ?int $tries = null,
        public int|array|null $backoff = null,
        public ?bool $asUser = null,
        public ?string $capture = null,
        public ?string $when = null,
        public bool $keepMissing = false,
        public string|array $except = [],
    ) {}
}
