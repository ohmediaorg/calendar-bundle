<?php

namespace OHMedia\CalendarBundle\Twig;

use OHMedia\CalendarBundle\Service\CalendarManager;
use OHMedia\WysiwygBundle\Twig\AbstractWysiwygExtension;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Twig\Environment;
use Twig\TwigFunction;

class CalendarExtension extends AbstractWysiwygExtension
{
    private bool $includeScripts = true;

    public function __construct(
        private CalendarManager $calendarManager,
        #[Autowire('%oh_media_timezone.timezone%')]
        private string $defaultTimezone,
        #[Autowire('%oh_media_calendar.theme%')]
        private string $theme,
    ) {
    }

    public function getFunctions(): array
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
            $palette = null;
        } else {
            list($theme, $palette) = explode('-', $this->theme);
        }

        return $env->render('@OHMediaCalendar/calendar.html.twig', [
            'include_scripts' => $includeScripts,
            'theme' => $theme,
            'palette' => $palette,
            'tags' => $this->calendarManager->getTags(),
            'timezone' => $this->defaultTimezone,
        ]);
    }
}
