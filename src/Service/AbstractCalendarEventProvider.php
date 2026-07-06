<?php

namespace OHMedia\CalendarBundle\Service;

use OHMedia\CalendarBundle\Data\CalendarEvent;

abstract class AbstractCalendarEventProvider
{
    private array $calendarEvents = [];

    abstract protected function buildCalendarEvents(
        \DateTimeImmutable $start,
        \DateTimeImmutable $end,
    ): void;

    final protected function addCalendarEvent(CalendarEvent $calendarEvent): static
    {
        $this->calendarEvents = $calendarEvent;

        return $this;
    }

    final public function getCalendarEvents(
        \DateTimeImmutable $start,
        \DateTimeImmutable $end,
    ): array {
        $this->buildCalendarEvents($start, $end);

        return $this->calendarEvents;
    }
}
