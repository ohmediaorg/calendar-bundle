<?php

namespace OHMedia\CalendarBundle\Service;

use OHMedia\CalendarBundle\Data\CalendarEvent;
use OHMedia\CalendarBundle\Data\CalendarTag;

abstract class AbstractCalendarDataProvider
{
    private array $calendarEvents = [];
    private array $calendarTags = [];

    abstract protected function buildCalendarEvents(
        \DateTimeImmutable $start,
        \DateTimeImmutable $end,
    ): void;

    abstract protected function buildCalendarTags(): void;

    final protected function addCalendarEvent(CalendarEvent $calendarEvent): static
    {
        $this->calendarEvents[] = $calendarEvent;

        return $this;
    }

    final public function getCalendarEvents(
        \DateTimeImmutable $start,
        \DateTimeImmutable $end,
    ): array {
        $this->buildCalendarEvents($start, $end);

        return $this->calendarEvents;
    }

    final protected function addCalendarTag(CalendarTag $calendarTag): static
    {
        $this->calendarTags[] = $calendarTag;

        return $this;
    }

    final public function getCalendarTags(): array
    {
        $this->buildCalendarTags();

        return $this->calendarTags;
    }
}
