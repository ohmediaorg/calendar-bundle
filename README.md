# Calendar Output

A shortcode `{{calendar()}}` is available.

## Calendar Styles

You can override the default "classic" theme by creating
`/config/packages/oh_media_calendar.yaml`:

```yaml
oh_media_calendar:
    theme: monarch-blue
```

The full list of themes:

1. monarch-blue
1. monarch-green
1. monarch-purple
1. monarch-red
1. monarch-yellow
1. forma-blue
1. forma-green
1. forma-purple
1. forma-red
1. breezy-amber
1. breezy-emerald
1. breezy-indigo
1. breezy-rose
1. pulse-blue
1. pulse-green
1. pulse-purple
1. pulse-red
1. classic

## Tag Styles

Use the following style structure:

```scss
.calendar-tags {

  .calendar-tag {

    &.calendar-tag--selected {

    }
  }
}
```

Each `.calendar-tag` will have the following CSS variables:

```
--calendar-tag-background-color
--calendar-tag-border-color
--calendar-tag-color
```

## Customizing Tags

Create the following template to override the bundle template:
`templates/bundles/OHMediaCalendarBundle/tags.html.twig`.

The data attribute `data-calendar-tag` and class `calendar-tag--selected` must
remain intact.

# Calendar Data

Extend `OHMedia\CalendarBundle\Service\AbstractCalenderEventProvider`.

## Events

Implement the function `buildCalendarEvents` similar to the following:

```php
protected function buildCalendarEvents(
    \DateTimeImmutable $start,
    \DateTimeImmutable $end,
    array $tagIds,
): void {
    // fetch your data
    // $start and $end will be in UTC
    $data;

    foreach ($data as $item) {
        $calendarEvent = new CalendarEvent(
            start: ...,
            end: ...,
            title: ...,
            url: ...,
        );

        $this->addCalendarEvent($calendarEvent);
    }
}
```

## Tags

Implement the function `buildCalendarTags` similar to the following:

```php
protected function buildCalendarTags(): void
{
    // fetch your data
    $data;

    foreach ($data as $item) {
        $this->addCalendarTag(
            text: ...,
            // if this tag is selected on the FE
            // this id will be in the tagIds array
            // of the buildCalendarEvents function
            id: ...,
            backgroundColor: ...,
            textColor: ...,
        );
    }
}
```
