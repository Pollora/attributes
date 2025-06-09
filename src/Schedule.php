<?php

declare(strict_types=1);

namespace Pollora\Attributes;

use Attribute;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Pollora\Attributes\Contracts\HandlesAttributes;
use Pollora\Hook\Infrastructure\Services\Action as ActionService;
use ReflectionClass;
use ReflectionMethod;

#[Attribute(Attribute::TARGET_METHOD)]
class Schedule implements HandlesAttributes
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
     * @param  mixed  $container  Service locator used to resolve dependencies.
     * @param  Attributable  $instance  Instance of the class containing the method to schedule.
     * @param  ReflectionClass|ReflectionMethod  $context  The reflection context.
     * @param  object  $attribute  The attribute instance.
     */
    public function handle($container, Attributable $instance, ReflectionClass|ReflectionMethod $context, object $attribute): void
    {
        // Ensure the context is a method
        if (! ($context instanceof ReflectionMethod)) {
            return;
        }

        // Retrieve the Action service from the locator - handle both modern and legacy containers
        if (method_exists($container, 'make')) {
            $actionService = $container->make(ActionService::class);
        } elseif (method_exists($container, 'get')) {
            $actionService = $container->get(ActionService::class);
        } else {
            // Fallback for other container implementations
            $actionService = null;
        }

        if (! $actionService) {
            return;
        }

        // Use the attribute properties or fall back to instance values
        $hookName = $attribute->hook ?? $this->generateHookName($context);
        $recurrence = $attribute->recurrence ?? $this->recurrence;
        $args = $attribute->args ?? $this->args;

        if (is_array($recurrence)) {
            $this->registerCustomSchedule($hookName, $recurrence);
        }

        $actionService->add('init', function () use ($instance, $hookName, $context, $actionService, $recurrence, $args): void {
            $actionService->add($hookName, [$instance, $context->getName()]);

            if (! $this->isEventScheduled($hookName, $args)) {
                $this->scheduleEvent($hookName, $recurrence, $args);
            }
        });
    }

    /**
     * Validates a predefined WordPress recurrence schedule.
     *
     * @param  string  $recurrence  The recurrence schedule name.
     *
     * @throws InvalidArgumentException If the recurrence schedule is invalid.
     */
    private function validateRecurrence(string $recurrence): void
    {
        if (! in_array($recurrence, self::DEFAULT_SCHEDULES)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Invalid recurrence schedule "%s". Allowed schedules are: %s',
                    $recurrence,
                    implode(', ', self::DEFAULT_SCHEDULES)
                )
            );
        }
    }

    /**
     * Validates a custom recurrence schedule definition.
     *
     * @param  array  $recurrence  The custom recurrence schedule definition.
     *
     * @throws InvalidArgumentException If the custom recurrence schedule is invalid.
     */
    private function validateCustomRecurrence(array $recurrence): void
    {
        if (! isset($recurrence['interval']) || ! is_numeric($recurrence['interval'])) {
            throw new InvalidArgumentException(
                'Custom recurrence schedule must have a numeric "interval" key.'
            );
        }

        if (! isset($recurrence['display']) || ! is_string($recurrence['display'])) {
            throw new InvalidArgumentException(
                'Custom recurrence schedule must have a string "display" key.'
            );
        }
    }

    /**
     * Generates a hook name based on the class and method names.
     *
     * @param  ReflectionMethod  $method  The method reflection.
     * @return string The generated hook name.
     */
    private function generateHookName(ReflectionMethod $method): string
    {
        $className = $method->getDeclaringClass()->getShortName();
        $methodName = $method->getName();

        return Str::snake($className).'_'.Str::snake($methodName);
    }

    /**
     * Registers a custom schedule with WordPress.
     *
     * @param  string  $hookName  The hook name.
     * @param  array  $schedule  The custom schedule definition.
     */
    private function registerCustomSchedule(string $hookName, array $schedule): void
    {
        add_filter('cron_schedules', function (array $schedules) use ($hookName, $schedule): array {
            $schedules[$hookName] = [
                'interval' => $schedule['interval'],
                'display' => $schedule['display'],
            ];

            return $schedules;
        });
    }

    /**
     * Checks if an event is already scheduled.
     *
     * @param  string  $hook  The hook name to check.
     * @param  array  $args  The arguments to pass to the scheduled method.
     * @return bool True if the event is already scheduled, false otherwise.
     */
    private function isEventScheduled(string $hook, array $args = []): bool
    {
        return (bool) wp_next_scheduled($hook, $args);
    }

    /**
     * Schedules the event with WordPress cron.
     *
     * @param  string  $hook  The hook name to schedule.
     * @param  string|array  $recurrence  The recurrence schedule.
     * @param  array  $args  The arguments to pass to the scheduled method.
     */
    private function scheduleEvent(string $hook, string|array $recurrence, array $args): void
    {
        $timestamp = time();
        $recurrence = is_string($recurrence) ? $recurrence : $hook;

        wp_schedule_event($timestamp, $recurrence, $hook, $args);
    }
}
