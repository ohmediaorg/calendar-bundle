<?php

namespace OHMedia\CalendarBundle\Data;

class CalendarTag
{
    public function __construct(
        public readonly string $text,
        public readonly string $className,
        public readonly string $backgroundColor,
        public readonly string $borderColor,
        public readonly string $textColor,
    ) {
    }
}
