<?php

namespace OHMedia\CalendarBundle\Twig;

use OHMedia\WysiwygBundle\Extension\AbstractWysiwygExtension;
use Twig\Environment;
use Twig\TwigFunction;

class CalendarExtension extends AbstractWysiwygExtension
{
    private bool $includeScript = true;

    public function getFilters(): array
    {
        return [
            new TwigFunction('calendar', [$this, 'calendar'], [
                'is_safe' => ['html'],
                'needs_environment' => true,
            ]),
        ];
    }

    public function calendar(Environment $env): string
    {
        $includeScript = $this->includeScript;

        $this->includeScript = false;

        return $env->render('@OHMediaCalendar/calendar.html.twig', [
            'include_script' => $includeScript,
        ]);
    }
}
