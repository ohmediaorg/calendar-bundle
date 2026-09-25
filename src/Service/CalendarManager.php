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

    public function getValidRange(): array
    {
        $start = null;
        $end = null;

        foreach ($this->calendarDataProviders as $calendarDataProvider) {
            $cdpStart = $calendarDataProvider->getValidRangeStart();

            if ($cdpStart && (!$start || $cdpStart < $start)) {
                $start = $cdpStart;
            }

            $cdpEnd = $calendarDataProvider->getValidRangeEnd();

            if ($cdpEnd && (!$end || $cdpEnd > $end)) {
                $end = $cdpEnd;
            }
        }

        $validRange = [];

        if ($start) {
            $validRange['start'] = $start->format('Y-m-d');
        }

        if ($end) {
            $validRange['end'] = $end->format('Y-m-d');
        }

        return $validRange;
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
                $eventObjectEnd = clone $calendarEvent->end;

                if ($calendarEvent->allDay) {
                    // FC treats "end" as exclusive
                    // an allDay event from Tue-Thu will not span through Thu
                    // so it needs to be changed from Tue-Fri
                    $eventObjectEnd = $eventObjectEnd->modify('+1 day');
                }

                $eventObject = [
                    'allDay' => $calendarEvent->allDay,
                    'start' => $calendarEvent->start->format('c'),
                    'end' => $eventObjectEnd->format('c'),
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
