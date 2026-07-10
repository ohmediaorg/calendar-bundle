<?php

namespace OHMedia\CalendarBundle\Data;

class CalendarTag
{
    public function __construct(
        public readonly string $text,
        public readonly string $id,
        public readonly string $backgroundColor,
        public readonly string $textColor,
    ) {
    }
}
