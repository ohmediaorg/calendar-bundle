<?php

namespace OHMedia\CalendarBundle\Controller;

use OHMedia\BackendBundle\Routing\Attribute\Admin;
use OHMedia\CalendarBundle\Service\CalendarManager;
use OHMedia\TimezoneBundle\Service\Timezone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Admin]
class CalendarController extends AbstractController
{
    #[Route('/calendar-events-json', name: 'calendar_events_json', methods: ['GET'])]
    public function __invoke(
        CalendarManager $calendarManager,
        Request $request,
        Timezone $timezone,
    ): Response {
        $timezone = new \DateTimeZone($timezone->get());

        $start = $request->query->get('start');

        if ($start) {
            $start = new \DateTimeImmutable($start);
        } else {
            $start = new \DateTimeImmutable('Y-m-01 00:00:00', $timezone);
        }

        $end = $request->query->get('end');

        if ($end) {
            $end = new \DateTimeImmutable($end);
        } else {
            $end = new \DateTimeImmutable('Y-m-t 23:59:59', $timezone);
        }

        $tags = $request->query->all('tags', []);

        $json = $calendarManager->getEventsJson($start, $end, $timezone, $tags);

        return new JsonResponse($json);
    }
}
