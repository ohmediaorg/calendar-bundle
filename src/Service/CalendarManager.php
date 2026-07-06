<?php

namespace OHMedia\CalendarBundle\Service;

class CalendarManager
{
    private array $calendarDataProviders = [];

    public function addCalendarDataProvider(AbstractCalendarDataProvider $calendarDataProvider): self
    {
        $this->calendarDataProviders[] = $calendarDataProvider;

        return $this;
    }

    public function getEventsJson(
        \DateTimeImmutable $start,
        \DateTimeImmutable $end,
        \DateTimeZone $timezone,
    ): array {
        $json = [];

        foreach ($this->calendarDataProviders as $calendarDataProvider) {
            $calendarEvents = $calendarDataProvider->getCalendarEvents($start, $end);

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

    public function getTags(): array
    {
        $tags = [];

        foreach ($this->calendarDataProviders as $calendarDataProvider) {
            $calendarTags = $calendarDataProvider->getCalendarTags();

            foreach ($calendarTags as $calendarTag) {
                $tags[] = [
                    'text' => $calendarTag->text,
                    'className' => $calendarTag->className,
                    'backgroundColor' => $calendarTag->backgroundColor,
                    'borderColor' => $calendarTag->borderColor,
                    'textColor' => $calendarTag->textColor,
                ];
            }
        }

        usort($tags, function ($a, $b) {
            return $a['text'] <=> $b['text'];
        });

        return $tags;
    }
}
