<?php

declare(strict_types=1);

namespace Pollora\Attributes;

use Attribute;
use Illuminate\Support\Str;
use InvalidArgumentException;
use ReflectionMethod;
use Pollora\Support\Facades\Action;

#[Attribute(Attribute::TARGET_METHOD)]
class Schedule
{
    /**
     * Default WordPress recurrence schedules
     */
    private const DEFAULT_SCHEDULES = [
        'hourly',
        'twicedaily',
        'daily',
        'weekly'
    ];

    /**
     * @param string|array $recurrence Either a predefined schedule name or a custom schedule array
     * @param string|null $hook Optional custom hook name, if null will use class and method name
     * @param array $args Arguments to pass to the scheduled function
     * @throws InvalidArgumentException
     */
    public function __construct(
        private string|array $recurrence,
        private ?string $hook = null,
        private array $args = []
    ) {
        if (is_string($this->recurrence)) {
            $this->validateRecurrence($this->recurrence);
        } elseif (is_array($this->recurrence)) {
            $this->validateCustomRecurrence($this->recurrence);
        }
    }

    /**
     * Handle the scheduling of the event
     */
    public function handle(object $instance, ReflectionMethod $method): void
    {
        // If no hook name provided, generate one from class and method
        $hookName = $this->hook ?? $this->generateHookName($instance, $method);

        // Register custom schedule if provided
        if (is_array($this->recurrence)) {
            $this->registerCustomSchedule($hookName, $this->recurrence);
        }

        Action::add('init', function() use ($instance, $hookName, $method) {
            // Add action hook for the scheduled event
            Action::add($hookName, [$instance, $method->getName()]);
            // Schedule the event if it's not already scheduled
            if (!$this->isEventScheduled($hookName)) {
                $this->scheduleEvent($hookName);
            }
        });
    }

    /**
     * Generate a hook name from class and method if none provided
     */
    /**
     * Generate a hook name from class and method if none provided
     */
    private function generateHookName(object $instance, ReflectionMethod $method): string
    {
        // Get the short name of the class from the method's class
        $className = $method->getDeclaringClass()->getShortName();

        // Convert to snake_case and combine with method name
        $hookName = strtolower(sprintf(
            '%s_%s',
            Str::snake($className),
            Str::snake($method->getName())
        ));

        return $hookName;
    }

    /**
     * Validate predefined recurrence schedule
     * @throws InvalidArgumentException
     */
    private function validateRecurrence(string $recurrence): void
    {
        if (!in_array($recurrence, self::DEFAULT_SCHEDULES)) {
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
     * Validate custom recurrence array
     * @throws InvalidArgumentException
     */
    private function validateCustomRecurrence(array $recurrence): void
    {
        if (!isset($recurrence['interval']) || !is_numeric($recurrence['interval'])) {
            throw new InvalidArgumentException(
                'Custom recurrence must include a numeric interval in seconds'
            );
        }

        if (!isset($recurrence['display']) || !is_string($recurrence['display'])) {
            throw new InvalidArgumentException(
                'Custom recurrence must include a display name'
            );
        }
    }

    /**
     * Register a custom schedule with WordPress
     */
    private function registerCustomSchedule(string $name, array $schedule): void
    {
        add_filter('cron_schedules', function ($schedules) use ($name, $schedule) {
            $schedules[$name] = [
                'interval' => $schedule['interval'],
                'display' => $schedule['display']
            ];
            return $schedules;
        });
    }

    /**
     * Check if an event is already scheduled
     */
    private function isEventScheduled(string $hook): bool
    {
        return (bool) wp_next_scheduled($hook, $this->args);
    }

    /**
     * Schedule the event with WordPress
     */
    private function scheduleEvent(string $hook): void
    {
        $timestamp = time();
        $recurrence = is_string($this->recurrence) ? $this->recurrence : $hook;

        wp_schedule_event($timestamp, $recurrence, $hook, $this->args);
    }
}
