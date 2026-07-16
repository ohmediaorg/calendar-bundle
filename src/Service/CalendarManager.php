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

            if (!$tagIds && $tags) {
                // tags are selected, but none for this provider
                continue;
            }

            $calendarEvents = $calendarDataProvider->getCalendarEvents($start, $end, $tagIds);

            foreach ($calendarEvents as $calendarEvent) {
                // allDay events need to exclude the time portion
                $dateFormat = $calendarEvent->allDay ? 'Y-m-d' : 'c';

                $end = $calendarEvent->end;

                if ($calendarEvent->allDay) {
                    // FC treats "end" as exclusive
                    // an allDay event from Tue-Thu will not span through Thu
                    // so it needs to be changed from Tue-Fri
                    $end = $end->modify('+1 day');
                }

                $eventObject = [
                    'allDay' => $calendarEvent->allDay,
                    'start' => $calendarEvent->start->format($dateFormat),
                    'end' => $end->format($dateFormat),
                    'title' => $calendarEvent->title,
                    'url' => $calendarEvent->url ?? '',
                    'className' => implode(' ', $calendarEvent->classNames),
                    'color' => $calendarEvent->backgroundColor ?? '',
                    'contrastColor' => $calendarEvent->textColor ?? '',
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
