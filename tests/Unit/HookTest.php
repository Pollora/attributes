<?php

declare(strict_types=1);

use Pollora\Attributes\Hook;

/**
 * Test class for the abstract Hook class
 * This test focuses on the base functionality of the Hook class
 * which is extended by Action and Filter attributes
 */

// Create a concrete implementation of the abstract Hook class for testing
class ConcreteHook extends Hook
{
    public function handle(
        $serviceLocator,
        object $instance,
        ReflectionClass|ReflectionMethod $context,
        object $attribute
    ): void {
        // Implementation for testing
    }
}

it('initializes with default priority', function () {
    $hook = new ConcreteHook('test_hook');
    expect($hook->hook)->toBe('test_hook')
        ->and($hook->priority)->toBe(10);
});

it('initializes with custom priority', function () {
    $hook = new ConcreteHook('test_hook', 20);
    expect($hook->hook)->toBe('test_hook')
        ->and($hook->priority)->toBe(20);
});

it('stores hook name correctly', function () {
    $hookName = 'custom_hook_name';
    $hook = new ConcreteHook($hookName);
    expect($hook->hook)->toBe($hookName);
});

it('allows priority to be a negative number', function () {
    $priority = -1;
    $hook = new ConcreteHook('test_hook', $priority);
    expect($hook->priority)->toBe($priority);
});

it('allows priority to be zero', function () {
    $priority = 0;
    $hook = new ConcreteHook('test_hook', $priority);
    expect($hook->priority)->toBe($priority);
});

it('allows priority to be a large number', function () {
    $priority = 9999;
    $hook = new ConcreteHook('test_hook', $priority);
    expect($hook->priority)->toBe($priority);
});

afterEach(function () {
    Mockery::close();
});
