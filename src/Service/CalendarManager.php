<?php

namespace OHMedia\CalendarBundle\Service;

class CalendarManager
{
    private array $calendarEventProviders = [];

    public function addCalendarEventProvider(AbstractCalendarEventProvider $calendarEventProvider): self
    {
        $this->calendarEventProviders[] = $calendarEventProvider;

        return $this;
    }

    public function getJson(
        \DateTimeImmutable $start,
        \DateTimeImmutable $end,
        \DateTimeZone $timezone,
    ) {
        $json = [];

        foreach ($this->calendarEventProviders as $calendarEventProvider) {
            $calendarEvents = $calendarEventProvider->getCalendarEvents($start, $end);

            foreach ($calendarEvents as $calendarEvent) {
                $eventObject = [
                    'allDay' => $calendarEvent->allDay,
                    'start' => $calendarEvent->start->setTimezone($timezone)->format('c'),
                    'end' => $calendarEvent->end->setTimezone($timezone)->format('c'),
                    'title' => $calendarEvent->title,
                    'classNames' => $calendarEvent->classNames,
                    'backgroundColor' => $calendarEvent->backgroundColor,
                    'borderColor' => $calendarEvent->borderColor,
                    'textColor' => $calendarEvent->textColor,
                ];

                if ($calendarEvent->url) {
                    $eventObject['url'] = $calendarEvent->url;
                }

                $json[] = $eventObject;
            }
        }

        return $json;
    }
}
