<?php

namespace OHMedia\CalendarBundle;

use OHMedia\CalendarBundle\DependencyInjection\Compiler\CalendarPass;
use OHMedia\CalendarBundle\Service\AbstractCalendarEventProvider;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class OHMediaCalendarBundle extends AbstractBundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new CalendarPass());
    }

    public function loadExtension(
        array $config,
        ContainerConfigurator $containerConfigurator,
        ContainerBuilder $containerBuilder,
    ): void {
        $containerConfigurator->import('../config/services.yaml');

        $containerBuilder->registerForAutoconfiguration(AbstractCalendarEventProvider::class)
            ->addTag('oh_media_calendar.calendar_event_provider')
        ;
    }
}
