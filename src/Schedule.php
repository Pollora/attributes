<?php

declare(strict_types=1);

namespace Pollora\Attributes;

use Attribute;
use Pollora\Schedule\Every;
use Pollora\Schedule\Interval;

/**
 * Schedule Attribute
 *
 * This attribute is used to mark methods for scheduled execution using WordPress cron.
 * It supports various recurrence patterns including predefined WordPress schedules,
 * Every enum values, Interval instances, and custom array definitions.
 *
 * The actual scheduling logic is handled by the ScheduleDiscovery class in the
 * discovery system, which scans for methods with this attribute and registers
 * them as WordPress cron events.
 *
 * @package Pollora\Attributes
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Schedule
{
    /**
     * Schedule attribute constructor.
     *
     * Creates a new Schedule attribute instance that defines when and how a method
     * should be executed as a scheduled task.
     *
     * @param string|array<string,mixed>|Every|Interval $recurrence The schedule recurrence definition
     *   - string: WordPress built-in schedule ('hourly', 'daily', 'weekly', 'twicedaily')
     *   - array: Custom schedule with 'interval' (seconds) and 'display' (name) keys
     *   - Every: Enum for predefined intervals (Every::HOUR, Every::DAY, etc.)
     *   - Interval: Custom interval instance with precise timing control
     * @param string|null $hook Optional custom hook name. If null, generated from class and method names
     * @param array<mixed> $args Optional arguments to pass to the scheduled method
     */
    public function __construct(
        public readonly string|array|Every|Interval $recurrence,
        public readonly ?string $hook = null,
        public readonly array $args = []
    ) {
        // No validation here - will be handled by ScheduleDiscovery
    }

}
