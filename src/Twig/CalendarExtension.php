<?php

namespace OHMedia\CalendarBundle\Twig;

use OHMedia\WysiwygBundle\Extension\AbstractWysiwygExtension;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Twig\Environment;
use Twig\TwigFunction;

class CalendarExtension extends AbstractWysiwygExtension
{
    private bool $includeScripts = true;

    public function __construct(
        #[Autowire('%oh_media_calendar.theme%')]
        private string $theme,
    ) {
    }

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
        $includeScripts = $this->includeScripts;

        $this->includeScripts = false;

        if ('classic' === $this->theme) {
            $theme = $this->theme;
            $pallette = null;
        } else {
            list($theme, $pallette) = explode('-', $this->theme);
        }

        return $env->render('@OHMediaCalendar/calendar.html.twig', [
            'include_scripts' => $includeScripts,
            'theme' => $theme,
            'pallette' => $pallete,
        ]);
    }
}
