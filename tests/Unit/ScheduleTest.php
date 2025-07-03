<?php

declare(strict_types=1);

use Pollora\Attributes\Schedule;
use Pollora\Schedule\Every;
use Pollora\Schedule\Interval;

/**
 * Schedule Attribute Tests
 *
 * Tests for the simplified Schedule attribute that now only contains properties
 * and delegates all processing logic to the ScheduleDiscovery service.
 */

it('creates Schedule attribute with string recurrence', function () {
    $schedule = new Schedule('daily');

    expect($schedule)->toBeInstanceOf(Schedule::class);
    expect($schedule->recurrence)->toBe('daily');
    expect($schedule->hook)->toBeNull();
    expect($schedule->args)->toBe([]);
});

it('creates Schedule attribute with custom hook name', function () {
    $schedule = new Schedule('hourly', 'custom_hook_name');

    expect($schedule->recurrence)->toBe('hourly');
    expect($schedule->hook)->toBe('custom_hook_name');
    expect($schedule->args)->toBe([]);
});

it('creates Schedule attribute with arguments', function () {
    $args = ['type' => 'full', 'force' => true];
    $schedule = new Schedule('daily', null, $args);

    expect($schedule->recurrence)->toBe('daily');
    expect($schedule->hook)->toBeNull();
    expect($schedule->args)->toBe($args);
});

it('creates Schedule attribute with all parameters', function () {
    $args = ['batch_size' => 100];
    $schedule = new Schedule('weekly', 'weekly_cleanup', $args);

    expect($schedule->recurrence)->toBe('weekly');
    expect($schedule->hook)->toBe('weekly_cleanup');
    expect($schedule->args)->toBe($args);
});

it('creates Schedule attribute with array recurrence', function () {
    $recurrence = ['interval' => 3600, 'display' => 'Every Hour'];
    $schedule = new Schedule($recurrence);

    expect($schedule->recurrence)->toBe($recurrence);
    expect($schedule->hook)->toBeNull();
    expect($schedule->args)->toBe([]);
});

it('creates Schedule attribute with Every enum', function () {
    $schedule = new Schedule(Every::DAY);

    expect($schedule->recurrence)->toBe(Every::DAY);
    expect($schedule->hook)->toBeNull();
    expect($schedule->args)->toBe([]);
});

it('creates Schedule attribute with Interval instance', function () {
    $interval = new Interval(hours: 2, minutes: 30);
    $schedule = new Schedule($interval);

    expect($schedule->recurrence)->toBe($interval);
    expect($schedule->hook)->toBeNull();
    expect($schedule->args)->toBe([]);
});

it('creates Schedule attribute with complex Every enum and parameters', function () {
    $args = ['source' => 'api', 'limit' => 50];
    $schedule = new Schedule(Every::MONTH, 'monthly_sync', $args);

    expect($schedule->recurrence)->toBe(Every::MONTH);
    expect($schedule->hook)->toBe('monthly_sync');
    expect($schedule->args)->toBe($args);
});

it('creates Schedule attribute with complex Interval and parameters', function () {
    $interval = new Interval(days: 1, hours: 12, minutes: 30);
    $args = ['cleanup_type' => 'deep'];
    $schedule = new Schedule($interval, 'complex_cleanup', $args);

    expect($schedule->recurrence)->toBe($interval);
    expect($schedule->hook)->toBe('complex_cleanup');
    expect($schedule->args)->toBe($args);
});

it('stores recurrence as readonly property', function () {
    $schedule = new Schedule('daily');
    
    expect($schedule->recurrence)->toBe('daily');
    
    // Verify property is readonly by checking it cannot be modified
    $reflection = new ReflectionClass($schedule);
    $property = $reflection->getProperty('recurrence');
    expect($property->isReadOnly())->toBeTrue();
});

it('stores hook as readonly property', function () {
    $schedule = new Schedule('daily', 'test_hook');
    
    expect($schedule->hook)->toBe('test_hook');
    
    // Verify property is readonly
    $reflection = new ReflectionClass($schedule);
    $property = $reflection->getProperty('hook');
    expect($property->isReadOnly())->toBeTrue();
});

it('stores args as readonly property', function () {
    $args = ['key' => 'value'];
    $schedule = new Schedule('daily', null, $args);
    
    expect($schedule->args)->toBe($args);
    
    // Verify property is readonly
    $reflection = new ReflectionClass($schedule);
    $property = $reflection->getProperty('args');
    expect($property->isReadOnly())->toBeTrue();
});

it('has correct PHP attribute configuration', function () {
    $reflection = new ReflectionClass(Schedule::class);
    $attributes = $reflection->getAttributes(Attribute::class);
    
    expect($attributes)->toHaveCount(1);
    
    $attribute = $attributes[0]->newInstance();
    expect($attribute->flags)->toBe(Attribute::TARGET_METHOD);
});

it('accepts all supported recurrence types without validation', function () {
    // No validation should happen in the attribute constructor
    // All validation is now handled by ScheduleDiscovery
    
    // String recurrence
    expect(fn () => new Schedule('daily'))->not->toThrow(Exception::class);
    expect(fn () => new Schedule('invalid_schedule'))->not->toThrow(Exception::class); // No validation
    
    // Array recurrence
    expect(fn () => new Schedule(['interval' => 3600, 'display' => 'Valid']))->not->toThrow(Exception::class);
    expect(fn () => new Schedule(['invalid' => 'array']))->not->toThrow(Exception::class); // No validation
    
    // Every enum
    expect(fn () => new Schedule(Every::HOUR))->not->toThrow(Exception::class);
    expect(fn () => new Schedule(Every::MONTH))->not->toThrow(Exception::class);
    
    // Interval instance
    $interval = new Interval(minutes: 30);
    expect(fn () => new Schedule($interval))->not->toThrow(Exception::class);
});