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
        array $tags,
    ): array {
        $json = [];

        $tagsByProvider = [];

        foreach ($tags as $tag) {
            list($provider, $id) = explode(':', $tag);

            if (!isset($tagsByProvider[$provider])) {
                $tagsByProvider[$provider] = [];
            }

            $tagsByProvider[$provider][] = $id;
        }

        $utc = new \DateTimeZone('UTC');

        $start = $start->setTimezone($utc);
        $end = $end->setTimezone($utc);

        foreach ($this->calendarDataProviders as $calendarDataProvider) {
            $tagIds = $tagsByProvider[$calendarDataProvider::class] ?? [];

            $calendarEvents = $calendarDataProvider->getCalendarEvents($start, $end, $tagIds);

            foreach ($calendarEvents as $calendarEvent) {
                $eventObject = [
                    'allDay' => $calendarEvent->allDay,
                    'start' => $calendarEvent->start->format('c'),
                    'end' => $calendarEvent->end->format('c'),
                    'title' => $calendarEvent->title,
                    'url' => $calendarEvent->url ?? '',
                    'className' => implode(' ', $calendarEvent->classNames),
                    'backgroundColor' => $calendarEvent->backgroundColor ?? '',
                    'borderColor' => $calendarEvent->borderColor ?? '',
                    'textColor' => $calendarEvent->textColor ?? '',
                ];

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
                    'id' => $calendarTag->id,
                    'text' => $calendarTag->text,
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
