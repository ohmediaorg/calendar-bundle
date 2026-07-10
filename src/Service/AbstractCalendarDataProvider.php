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
        array $tagIds,
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
        array $tagIds,
    ): array {
        $this->buildCalendarEvents($start, $end, $tagIds);

        return $this->calendarEvents;
    }

    final protected function addCalendarTag(
        int|string $id,
        string $text,
        string $backgroundColor,
        string $textColor,
    ): static {
        $calendarTag = new CalendarTag(
            text: $text,
            id: static::class.':'.$id,
            backgroundColor: $backgroundColor,
            textColor: $textColor,
        );

        $this->calendarTags[] = $calendarTag;

        return $this;
    }

    final public function getCalendarTags(): array
    {
        $this->buildCalendarTags();

        return $this->calendarTags;
    }
}
