<?php

namespace OHMedia\CalendarBundle\DependencyInjection\Compiler;

use OHMedia\CalendarBundle\Service\CalendarManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class CalendarPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        // always first check if the primary service is defined
        if (!$container->has(CalendarManager::class)) {
            return;
        }

        $definition = $container->findDefinition(CalendarManager::class);

        $tagged = $container->findTaggedServiceIds('oh_media_calendar.calendar_data_provider');

        foreach ($tagged as $id => $tags) {
            $definition->addMethodCall('addCalendarDataProvider', [new Reference($id)]);
        }
    }
}
