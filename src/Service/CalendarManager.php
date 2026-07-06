<?php

namespace OHMedia\CalendarBundle\Service;

class CalendarManager
{
    private array $calendarItemProviders = [];

    public function addCalendarItemProvider(AbstractCalendarItemProvider $calendarItemProvider): self
    {
        $this->calendarItemProviders[] = $calendarItemProvider;

        return $this;
    }

    public function getJson(
        \DateTimeImmutable $start,
        \DateTimeImmutable $end,
        \DateTimeZone $timezone,
    ) {
        $json = [];

        foreach ($this->calendarItemProviders as $calendarItemProvider) {
            $calendarEvents = $calendarItemProvider->getCalendarEvents($start, $end);

            foreach ($calendarEvents as $calendarEvent) {
                $json[] = [
                    'allDay' => $calendarEvent->allDay,
                    'start' => $calendarEvent->start->setTimezone()->format('c'),
                    'end' => $calendarEvent->end->setTimezone()->format('c'),
                    'title' => $calendarEvent->title,
                    'url' => $calendarEvent->url,
                    'classNames' => $calendarEvent->classNames,
                    'backgroundColor' => $calendarEvent->backgroundColor,
                    'borderColor' => $calendarEvent->borderColor,
                    'textColor' => $calendarEvent->textColor,
                ];
            }
        }

        return $json;
    }
}
