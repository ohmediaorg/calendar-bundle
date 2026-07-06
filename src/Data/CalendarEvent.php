<?php

namespace OHMedia\CalendarBundle\Data;

class CalendarEvent
{
    public readonly array $classNames;

    public function __construct(
        public readonly \DateTimeInterface $start,
        public readonly \DateTimeInterface $end,
        public readonly string $title,
        public readonly bool $allDay = false,
        public readonly ?string $url = null,
        public readonly ?string $backgroundColor = null,
        public readonly ?string $borderColor = null,
        public readonly ?string $textColor = null,
    ) {
        $this->classNames = [];
    }

    public function setClassNames(string ...$classNames)
    {
        $this->classNames = $classNames;
    }
}
