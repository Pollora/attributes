<?php

declare(strict_types=1);

namespace Pollora\Schedule;

final readonly class Interval
{
    public function __construct(
        public int $seconds = 0,
        public int $minutes = 0,
        public int $hours = 0,
        public int $days = 0,
        public int $weeks = 0,
        public string $display = 'Custom schedule'
    ) {}

    public function toScheduleArray(): array
    {
        return [
            'interval' => $this->totalSeconds(),
            'display' => $this->display,
        ];
    }

    public function totalSeconds(): int
    {
        return $this->seconds
             + $this->minutes * 60
             + $this->hours * 3600
             + $this->days * 86400
             + $this->weeks * 604800;
    }
}
