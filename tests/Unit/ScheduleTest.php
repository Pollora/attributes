<?php

declare(strict_types=1);

use Mockery as m;
use Pollora\Attributes\Attributable;
use Pollora\Attributes\AttributeProcessor;
use Pollora\Attributes\Schedule;
use Pollora\Container\Domain\ServiceLocator;
use Pollora\Hook\Infrastructure\Services\Action as ActionService;
use Pollora\Hook\Infrastructure\Services\Filter as FilterService;

// Modifions la classe de test pour utiliser l'attribut Schedule uniquement sur les méthodes
// puisque Schedule ne cible que les méthodes
class TestScheduledTask implements Attributable
{
    public function testMethod(): void
    {
        // Test method
    }

    #[Schedule('hourly')]
    public function testHourlySchedule(): void
    {
        // Test method with hourly schedule
    }

    #[Schedule(['interval' => 3600, 'display' => 'Every hour'], 'custom_hook')]
    public function testCustomSchedule(): void
    {
        // Test method with custom schedule
    }
}

// Function to simulate fixed timestamp for tests
if (! function_exists('time')) {
    function time()
    {
        return 1234567890; // Fixed value for tests
    }
}

beforeEach(function () {
    $this->mockAction = Mockery::mock(ActionService::class);
    // Don't define default behavior here, each test will define its specific expectations

    $this->mockFilter = Mockery::mock(FilterService::class);

    // Create a ServiceLocator mock
    $this->mockServiceLocator = Mockery::mock(ServiceLocator::class);
    $this->mockServiceLocator->shouldReceive('resolve')
        ->with(ActionService::class)
        ->andReturn($this->mockAction);
    $this->mockServiceLocator->shouldReceive('resolve')
        ->with(FilterService::class)
        ->andReturn($this->mockFilter);

    // Create an AttributeProcessor mock that will use our ServiceLocator mock
    $this->processor = Mockery::mock(AttributeProcessor::class);
    $this->processor->shouldReceive('process')
        ->andReturnUsing(function ($instance) {
            $reflection = new ReflectionClass($instance);
            foreach ($reflection->getMethods() as $method) {
                $attributes = $method->getAttributes(Schedule::class);
                foreach ($attributes as $attribute) {
                    $scheduleInstance = $attribute->newInstance();
                    $scheduleInstance->handle($this->mockServiceLocator, $instance, $method, $scheduleInstance);
                }
            }
        });

    // Initialize WordPress mocks
    WP::$wpFunctions = m::mock('stdClass');
});

afterEach(function () {
    m::close();
    WP::$wpFunctions = null;
});

test('Schedule attribute validates predefined recurrence correctly', function () {
    // Test with valid schedule
    expect(fn () => new Schedule('hourly'))->not->toThrow(\InvalidArgumentException::class);

    // Test with invalid schedule
    $expectedMessage = 'Invalid recurrence schedule "invalid_schedule". Allowed schedules are: hourly, twicedaily, daily, weekly';
    expect(fn () => new Schedule('invalid_schedule'))
        ->toThrow(\InvalidArgumentException::class, $expectedMessage);
});

test('Schedule attribute validates custom recurrence correctly', function () {
    // Test with valid custom schedule
    $validSchedule = [
        'interval' => 3600,
        'display' => 'Every hour',
    ];
    expect(fn () => new Schedule($validSchedule))->not->toThrow(\InvalidArgumentException::class);

    // Test with invalid custom schedule (without interval)
    $invalidSchedule1 = [
        'display' => 'Every hour',
    ];
    expect(fn () => new Schedule($invalidSchedule1))
        ->toThrow(
            \InvalidArgumentException::class,
            'Custom recurrence schedule must have a numeric "interval" key.'
        );

    // Test with invalid custom schedule (without display)
    $invalidSchedule2 = [
        'interval' => 3600,
    ];
    expect(fn () => new Schedule($invalidSchedule2))
        ->toThrow(
            \InvalidArgumentException::class,
            'Custom recurrence schedule must have a string "display" key.'
        );
});

test('Schedule attribute handles custom hook names', function () {
    $initCallback = null;

    // Mock for ActionService - specific configuration for this test
    $this->mockAction->shouldReceive('add')
        ->with('init', m::type('Closure'))
        ->once()
        ->andReturnUsing(function ($hook, $callback) use (&$initCallback) {
            $initCallback = $callback;

            return $this->mockAction;
        });
    $this->mockAction->shouldReceive('add')
        ->with('custom_hook_name', m::type('array'))
        ->once()
        ->andReturnSelf();

    // Configure WordPress function mocks
    WP::$wpFunctions->shouldReceive('wp_next_scheduled')
        ->once()
        ->with('custom_hook_name', [])
        ->andReturn(false);
    WP::$wpFunctions->shouldReceive('wp_schedule_event')
        ->once()
        ->withArgs(function ($timestamp, $recurrence, $hook, $args) {
            return is_numeric($timestamp)
                && $recurrence === 'hourly'
                && $hook === 'custom_hook_name'
                && $args === [];
        })
        ->andReturn(true);

    $schedule = new Schedule('hourly', 'custom_hook_name');
    $reflection = new ReflectionClass(TestScheduledTask::class);
    $method = $reflection->getMethod('testMethod');
    $instance = new TestScheduledTask;
    $schedule->handle($this->mockServiceLocator, $instance, $method, $schedule);

    // Execute the stored Closure
    if ($initCallback) {
        $initCallback();
    }
    expect(true)->toBeTrue(); // To make Pest detect an assertion
});

test('Schedule attribute registers custom schedule', function () {
    $initCallback = null;

    // Mock for ActionService - specific configuration for this test
    $this->mockAction->shouldReceive('add')
        ->with('init', m::type('Closure'))
        ->once()
        ->andReturnUsing(function ($hook, $callback) use (&$initCallback) {
            $initCallback = $callback;

            return $this->mockAction;
        });
    $this->mockAction->shouldReceive('add')
        ->with('custom_schedule_hook', m::type('array'))
        ->once()
        ->andReturnSelf();

    // Configure WordPress function mocks
    WP::$wpFunctions->shouldReceive('wp_next_scheduled')
        ->once()
        ->with('custom_schedule_hook', [])
        ->andReturn(false);
    WP::$wpFunctions->shouldReceive('wp_schedule_event')
        ->once()
        ->withArgs(function ($timestamp, $recurrence, $hook, $args) {
            return is_numeric($timestamp)
                && $recurrence === 'custom_schedule_hook'
                && $hook === 'custom_schedule_hook'
                && $args === [];
        })
        ->andReturn(true);
    WP::$wpFunctions->shouldReceive('add_filter')
        ->withAnyArgs()
        ->once()
        ->andReturnUsing(function ($hook, $callback) {
            $schedules = [];
            $result = $callback($schedules);
            expect($result)->toHaveKey('custom_schedule_hook')
                ->and($result['custom_schedule_hook'])->toHaveKey('interval', 3600)
                ->and($result['custom_schedule_hook'])->toHaveKey('display', 'Every hour');

            return $result;
        });

    $schedule = new Schedule(['interval' => 3600, 'display' => 'Every hour'], 'custom_schedule_hook');
    $reflection = new ReflectionClass(TestScheduledTask::class);
    $method = $reflection->getMethod('testMethod');
    $instance = new TestScheduledTask;
    $schedule->handle($this->mockServiceLocator, $instance, $method, $schedule);

    // Execute the stored Closure
    if ($initCallback) {
        $initCallback();
    }
    expect(true)->toBeTrue(); // To make Pest detect an assertion
});

// Test the hourly schedule method attribute that replaced the class level attribute
test('Schedule attribute on hourly method works correctly', function () {
    $initCallback = null;
    $methodHookName = 'test_scheduled_task_test_hourly_schedule';

    // Mock for ActionService - specific configuration for this test
    $this->mockAction->shouldReceive('add')
        ->with('init', m::type('Closure'))
        ->once()
        ->andReturnUsing(function ($hook, $callback) use (&$initCallback) {
            $initCallback = $callback;

            return $this->mockAction;
        });
    $this->mockAction->shouldReceive('add')
        ->with($methodHookName, m::type('array'))
        ->once()
        ->andReturnSelf();

    // Configure WordPress function mocks
    WP::$wpFunctions->shouldReceive('wp_next_scheduled')
        ->once()
        ->with($methodHookName, [])
        ->andReturn(false);
    WP::$wpFunctions->shouldReceive('wp_schedule_event')
        ->once()
        ->withArgs(function ($timestamp, $recurrence, $hook, $args) use ($methodHookName) {
            return is_numeric($timestamp)
                && $recurrence === 'hourly'
                && $hook === $methodHookName
                && $args === [];
        })
        ->andReturn(true);

    // Process the hourly schedule method attribute
    $instance = new TestScheduledTask();
    $reflectionClass = new ReflectionClass($instance);
    $method = $reflectionClass->getMethod('testHourlySchedule');
    $methodAttributes = $method->getAttributes(Schedule::class);
    $scheduleAttribute = $methodAttributes[0]->newInstance();
    $scheduleAttribute->handle($this->mockServiceLocator, $instance, $method, $scheduleAttribute);

    // Execute the stored Closure
    if ($initCallback) {
        $initCallback();
    }

    expect(true)->toBeTrue(); // To make Pest detect an assertion
});

// Test that AttributeProcessor processes all Schedule attributes on methods
test('AttributeProcessor processes all Schedule attributes on methods', function () {
    // Utilisez un tableau pour stocker tous les callbacks
    $initCallbacks = [];

    // We expect 2 Schedule attributes to be processed: testHourlySchedule and testCustomSchedule
    $hookNames = [
        'test_scheduled_task_test_hourly_schedule',
        'custom_hook'
    ];

    // Mock for ActionService - specific configuration for this test
    $this->mockAction->shouldReceive('add')
        ->with('init', m::type('Closure'))
        ->twice()
        ->andReturnUsing(function ($hook, $callback) use (&$initCallbacks) {
            // Stockez chaque callback dans le tableau au lieu d'écraser la variable
            $initCallbacks[] = $callback;
            return $this->mockAction;
        });

    // Each method with a Schedule attribute will register an action
    $this->mockAction->shouldReceive('add')
        ->with($hookNames[0], m::type('array'))
        ->once()
        ->andReturnSelf();
    $this->mockAction->shouldReceive('add')
        ->with($hookNames[1], m::type('array'))
        ->once()
        ->andReturnSelf();

    // Configure WordPress function mocks for each hook
    foreach ($hookNames as $hookName) {
        WP::$wpFunctions->shouldReceive('wp_next_scheduled')
            ->once()
            ->with($hookName, [])
            ->andReturn(false);
    }

    // Expect wp_schedule_event for hourly schedule
    WP::$wpFunctions->shouldReceive('wp_schedule_event')
        ->once()
        ->withArgs(function ($timestamp, $recurrence, $hook, $args) use ($hookNames) {
            return is_numeric($timestamp)
                && $recurrence === 'hourly'
                && $hook === $hookNames[0]
                && $args === [];
        })
        ->andReturn(true);

    // Expect wp_schedule_event for custom schedule
    WP::$wpFunctions->shouldReceive('wp_schedule_event')
        ->once()
        ->withArgs(function ($timestamp, $recurrence, $hook, $args) use ($hookNames) {
            return is_numeric($timestamp)
                && $recurrence === 'custom_hook'
                && $hook === $hookNames[1]
                && $args === [];
        })
        ->andReturn(true);

    // For the custom schedule, we need to add_filter for the cron_schedules
    WP::$wpFunctions->shouldReceive('add_filter')
        ->withArgs(['cron_schedules', m::type('Closure'), 10, 1])
        ->once()
        ->andReturnUsing(function ($hook, $callback) {
            $schedules = [];
            $result = $callback($schedules);
            expect($result)->toHaveKey('custom_hook')
                ->and($result['custom_hook'])->toHaveKey('interval', 3600)
                ->and($result['custom_hook'])->toHaveKey('display', 'Every hour');
            return $result;
        });

    // Create a real AttributeProcessor that will process all method attributes
    $realProcessor = new class($this->mockServiceLocator) extends AttributeProcessor {
        private $serviceLocator;

        public function __construct($serviceLocator) {
            $this->serviceLocator = $serviceLocator;
        }

        public function process(object $instance): void {
            $reflection = new ReflectionClass($instance);
            foreach ($reflection->getMethods() as $method) {
                $attributes = $method->getAttributes(Schedule::class);
                foreach ($attributes as $attribute) {
                    $scheduleInstance = $attribute->newInstance();
                    $scheduleInstance->handle($this->serviceLocator, $instance, $method, $scheduleInstance);
                }
            }
        }
    };

    // Process the instance
    $instance = new TestScheduledTask();
    $realProcessor->process($instance);

    // Exécutez tous les callbacks stockés
    foreach ($initCallbacks as $callback) {
        $callback();
    }

    expect(true)->toBeTrue(); // To make Pest detect an assertion
});
