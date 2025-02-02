<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Facade;
use Pollora\Attributes\Action;
use Pollora\Attributes\Attributable;
use Pollora\Attributes\AttributeProcessor;
use Pollora\Attributes\Filter;
use Pollora\Support\Facades\Filter as FilterFacade;

it('registers an action hook', function () {
    // Créer le mock avec les bons arguments attendus
    $mock = Mockery::mock('Pollora\Hook\Hook');

    // Utiliser Mockery::any() pour l'instance de TestClass
    $mock->shouldReceive('add')
        ->once()
        ->withArgs(function ($hook, $callback, $priority, $acceptedArgs) {
            return $hook === 'test_hook'
                && is_array($callback)
                && $callback[0] instanceof TestClass
                && $callback[1] === 'testMethod'
                && $priority === 10
                && $acceptedArgs === 0;
        })
        ->andReturn($mock);

    Facade::clearResolvedInstances();
    Facade::setFacadeApplication(['wp.action' => $mock]);

    $testClass = new TestClass;
    AttributeProcessor::process($testClass);
});

class TestClass implements Attributable
{
    #[Action('test_hook', priority: 10)]
    public function testMethod()
    {
        // Test method
    }
}

beforeEach(function () {
    Facade::clearResolvedInstances();
});

afterEach(function () {
    Mockery::close();
});

it('registers a filter hook and modifies value', function () {
    // Créer le mock
    $mock = Mockery::mock('Pollora\Hook\Hook');

    // Test avec withArgs pour vérifier le callback
    $mock->shouldReceive('add')
        ->once()
        ->withArgs(function ($hook, $callback, $priority, $acceptedArgs) {
            return $hook === 'test_filter'
                && is_array($callback)
                && $callback[0] instanceof TestFilterClass
                && $callback[1] === 'filterMethod'
                && $priority === 10
                && $acceptedArgs === 1;
        })
        ->andReturn($mock);

    // Configurer la façade
    Facade::clearResolvedInstances();
    Facade::setFacadeApplication(['wp.filter' => $mock]);

    $testClass = new TestFilterClass;
    AttributeProcessor::process($testClass);
});

// Test de l'exécution du filtre
it('executes filter and returns modified value', function () {
    $mock = Mockery::mock('Pollora\Hook\Hook');

    // Simuler l'ajout du filtre
    $mock->shouldReceive('add')->once()->andReturn($mock);

    // Simuler l'application du filtre
    $mock->shouldReceive('apply')
        ->once()
        ->with('test_filter', 'original value')
        ->andReturn('modified value');

    Facade::clearResolvedInstances();
    Facade::setFacadeApplication(['wp.filter' => $mock]);

    $testClass = new TestFilterClass;
    AttributeProcessor::process($testClass);

    // Tester l'application du filtre
    $result = FilterFacade::apply('test_filter', 'original value');
    expect($result)->toBe('modified value');
});

class TestFilterClass implements Attributable
{
    #[Filter('test_filter')]
    public function filterMethod(string $value): string
    {
        return 'modified '.$value;
    }
}

beforeEach(function () {
    Facade::clearResolvedInstances();
});

afterEach(function () {
    Mockery::close();
});
