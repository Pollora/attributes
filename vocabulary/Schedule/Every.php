<?php

declare(strict_types=1);

namespace Pollora\Schedule;

enum Every
{
    case HOUR;
    case TWICE_DAILY;
    case DAY;
    case WEEK;
    case MONTH;
    case YEAR;

    public function toScheduleKey(): string
    {
        return match ($this) {
            self::HOUR => 'hourly',
            self::TWICE_DAILY => 'twicedaily',
            self::DAY => 'daily',
            self::WEEK => 'weekly',
            self::MONTH => 'monthly',
            self::YEAR => 'yearly',
        };
    }

    public function isCustom(): bool
    {
        return in_array($this, [self::MONTH, self::YEAR], true);
    }

    public function toInterval(): Interval
    {
        return match ($this) {
            self::HOUR => new Interval(hours: 1, display: 'Once Hourly'),
            self::TWICE_DAILY => new Interval(hours: 12, display: 'Twice Daily'),
            self::DAY => new Interval(days: 1, display: 'Once Daily'),
            self::WEEK => new Interval(weeks: 1, display: 'Once Weekly'),
            self::MONTH => new Interval(days: 30, display: 'Once Monthly'),
            self::YEAR => new Interval(days: 365, display: 'Once Yearly'),
        };
    }
}
