<?php

namespace OHMedia\CalendarBundle\Data;

class CalendarEvent
{
    public function __construct(
        public readonly \DateTimeInterface $start,
        public readonly \DateTimeInterface $end,
        public readonly string $title,
        public readonly bool $allDay = false,
        public readonly ?string $url = null,
        public readonly ?string $backgroundColor = null,
        public readonly ?string $textColor = null,
        public readonly array $classNames = [],
    ) {
    }
}
