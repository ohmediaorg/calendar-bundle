<?php

namespace OHMedia\CalendarBundle\Service;

use OHMedia\WysiwygBundle\Shortcodes\AbstractShortcodeProvider;
use OHMedia\WysiwygBundle\Shortcodes\Shortcode;

class CalendarShortcodeProvider extends AbstractShortcodeProvider
{
    public function getTitle(): string
    {
        return 'Calendar';
    }

    public function buildShortcodes(): void
    {
        $this->addShortcode(new Shortcode(
            'Calendar',
            'calendar()'
        ));
    }
}
