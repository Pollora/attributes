<?php

declare(strict_types=1);

namespace Pollora\Attributes;

use Attribute;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Pollora\Support\Facades\Action;
use ReflectionMethod;

#[Attribute(Attribute::TARGET_METHOD)]
class Schedule
{
    /**
     * Default WordPress recurrence schedules.
     *
     * @var array<string>
     */
    private const DEFAULT_SCHEDULES = [
        'hourly',
        'twicedaily',
        'daily',
        'weekly',
    ];

    /**
     * Schedule constructor.
     *
     * @param  string|array  $recurrence  Either a predefined WordPress schedule name or a custom schedule definition.
     * @param  string|null  $hook  Optional custom hook name. If null, the hook name is generated from class and method names.
     * @param  array  $args  Arguments to pass to the scheduled method.
     *
     * @throws InvalidArgumentException If the recurrence schedule is invalid.
     */
    public function __construct(
        private readonly string|array $recurrence,
        private readonly ?string $hook = null,
        private readonly array $args = []
    ) {
        if (is_string($this->recurrence)) {
            $this->validateRecurrence($this->recurrence);
        } elseif (is_array($this->recurrence)) {
            $this->validateCustomRecurrence($this->recurrence);
        }
    }

    /**
     * Registers the scheduled event with WordPress.
     *
     * @param  object  $instance  Instance of the class containing the method to schedule.
     * @param  ReflectionMethod  $method  The method to invoke when the scheduled event runs.
     */
    public function handle(object $instance, ReflectionMethod $method): void
    {
        $hookName = $this->hook ?? $this->generateHookName($method);

        if (is_array($this->recurrence)) {
            $this->registerCustomSchedule($hookName, $this->recurrence);
        }

        Action::add('init', function () use ($instance, $hookName, $method): void {
            Action::add($hookName, [$instance, $method->getName()]);

            if (! $this->isEventScheduled($hookName)) {
                $this->scheduleEvent($hookName);
            }
        });
    }

    /**
     * Generates a unique hook name from the class and method names.
     *
     * @param  ReflectionMethod  $method  The reflection method to generate the hook from.
     * @return string Generated hook name in snake_case format.
     */
    private function generateHookName(ReflectionMethod $method): string
    {
        $className = $method->getDeclaringClass()->getShortName();

        return strtolower(sprintf(
            '%s_%s',
            Str::snake($className),
            Str::snake($method->getName())
        ));
    }

    /**
     * Validates a predefined recurrence schedule.
     *
     * @param  string  $recurrence  The recurrence schedule to validate.
     *
     * @throws InvalidArgumentException If the schedule name is invalid.
     */
    private function validateRecurrence(string $recurrence): void
    {
        if (! in_array($recurrence, self::DEFAULT_SCHEDULES)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Invalid recurrence schedule "%s". Valid schedules are: %s',
                    $recurrence,
                    implode(', ', self::DEFAULT_SCHEDULES)
                )
            );
        }
    }

    /**
     * Validates a custom recurrence schedule definition.
     *
     * @param  array  $recurrence  The custom recurrence definition to validate.
     *
     * @throws InvalidArgumentException If the recurrence definition lacks required keys or has invalid types.
     */
    private function validateCustomRecurrence(array $recurrence): void
    {
        if (! isset($recurrence['interval']) || ! is_numeric($recurrence['interval'])) {
            throw new InvalidArgumentException(
                'Custom recurrence must include a numeric interval in seconds'
            );
        }

        if (! isset($recurrence['display']) || ! is_string($recurrence['display'])) {
            throw new InvalidArgumentException(
                'Custom recurrence must include a display name'
            );
        }
    }

    /**
     * Registers a custom recurrence schedule with WordPress.
     *
     * @param  string  $name  The unique schedule name.
     * @param  array  $schedule  The custom recurrence definition (interval and display).
     */
    private function registerCustomSchedule(string $name, array $schedule): void
    {
        add_filter('cron_schedules', function (array $schedules) use ($name, $schedule) {
            $schedules[$name] = [
                'interval' => $schedule['interval'],
                'display' => $schedule['display'],
            ];

            return $schedules;
        });
    }

    /**
     * Checks if the event is already scheduled in WordPress.
     *
     * @param  string  $hook  The hook name to check.
     * @return bool True if the event is scheduled, false otherwise.
     */
    private function isEventScheduled(string $hook): bool
    {
        return (bool) wp_next_scheduled($hook, $this->args);
    }

    /**
     * Schedules the event with WordPress cron.
     *
     * @param  string  $hook  The hook name to schedule.
     */
    private function scheduleEvent(string $hook): void
    {
        $timestamp = time();
        $recurrence = is_string($this->recurrence) ? $this->recurrence : $hook;

        wp_schedule_event($timestamp, $recurrence, $hook, $this->args);
    }
}
