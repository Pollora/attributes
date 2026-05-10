<?php

declare(strict_types=1);

use Pollora\Schedule\Interval;

describe('Interval', function (): void {
    it('calculates total seconds from all units', function (): void {
        $interval = new Interval(
            seconds: 30,
            minutes: 5,
            hours: 2,
            days: 1,
            weeks: 1
        );

        // 30 + (5*60) + (2*3600) + (1*86400) + (1*604800) = 698430
        expect($interval->totalSeconds())->toBe(30 + 300 + 7200 + 86400 + 604800);
    });

    it('returns zero for empty interval', function (): void {
        $interval = new Interval();

        expect($interval->totalSeconds())->toBe(0);
    });

    it('calculates seconds from hours only', function (): void {
        $interval = new Interval(hours: 1);

        expect($interval->totalSeconds())->toBe(3600);
    });

    it('calculates seconds from days only', function (): void {
        $interval = new Interval(days: 30);

        expect($interval->totalSeconds())->toBe(2592000);
    });

    it('calculates seconds from weeks only', function (): void {
        $interval = new Interval(weeks: 1);

        expect($interval->totalSeconds())->toBe(604800);
    });

    it('returns schedule array with interval and display', function (): void {
        $interval = new Interval(hours: 12, display: 'Twice Daily');

        $array = $interval->toScheduleArray();

        expect($array)->toBe([
            'interval' => 43200,
            'display' => 'Twice Daily',
        ]);
    });

    it('uses default display text', function (): void {
        $interval = new Interval(minutes: 30);

        expect($interval->display)->toBe('Custom schedule');
    });

    it('is readonly', function (): void {
        $reflection = new ReflectionClass(Interval::class);

        expect($reflection->isReadOnly())->toBeTrue();
    });
});