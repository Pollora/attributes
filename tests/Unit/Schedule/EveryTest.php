<?php

declare(strict_types=1);

use Pollora\Schedule\Every;
use Pollora\Schedule\Interval;

describe('Every', function (): void {
    it('maps to correct WordPress schedule keys', function (): void {
        expect(Every::HOUR->toScheduleKey())->toBe('hourly');
        expect(Every::TWICE_DAILY->toScheduleKey())->toBe('twicedaily');
        expect(Every::DAY->toScheduleKey())->toBe('daily');
        expect(Every::WEEK->toScheduleKey())->toBe('weekly');
        expect(Every::MONTH->toScheduleKey())->toBe('monthly');
        expect(Every::YEAR->toScheduleKey())->toBe('yearly');
    });

    it('identifies custom schedules correctly', function (): void {
        expect(Every::HOUR->isCustom())->toBeFalse();
        expect(Every::TWICE_DAILY->isCustom())->toBeFalse();
        expect(Every::DAY->isCustom())->toBeFalse();
        expect(Every::WEEK->isCustom())->toBeFalse();
        expect(Every::MONTH->isCustom())->toBeTrue();
        expect(Every::YEAR->isCustom())->toBeTrue();
    });

    it('converts to correct Interval instances', function (): void {
        $hourInterval = Every::HOUR->toInterval();
        expect($hourInterval)->toBeInstanceOf(Interval::class);
        expect($hourInterval->totalSeconds())->toBe(3600);

        $twiceDailyInterval = Every::TWICE_DAILY->toInterval();
        expect($twiceDailyInterval->totalSeconds())->toBe(43200);

        $dayInterval = Every::DAY->toInterval();
        expect($dayInterval->totalSeconds())->toBe(86400);

        $weekInterval = Every::WEEK->toInterval();
        expect($weekInterval->totalSeconds())->toBe(604800);

        $monthInterval = Every::MONTH->toInterval();
        expect($monthInterval->totalSeconds())->toBe(2592000);

        $yearInterval = Every::YEAR->toInterval();
        expect($yearInterval->totalSeconds())->toBe(31536000);
    });

    it('provides meaningful display text in intervals', function (): void {
        expect(Every::HOUR->toInterval()->display)->toBe('Once Hourly');
        expect(Every::DAY->toInterval()->display)->toBe('Once Daily');
        expect(Every::WEEK->toInterval()->display)->toBe('Once Weekly');
        expect(Every::MONTH->toInterval()->display)->toBe('Once Monthly');
        expect(Every::YEAR->toInterval()->display)->toBe('Once Yearly');
    });

    it('generates valid schedule arrays for custom intervals', function (): void {
        $monthArray = Every::MONTH->toInterval()->toScheduleArray();

        expect($monthArray)->toHaveKeys(['interval', 'display']);
        expect($monthArray['interval'])->toBeInt();
        expect($monthArray['interval'])->toBeGreaterThan(0);
        expect($monthArray['display'])->toBeString();
    });
});