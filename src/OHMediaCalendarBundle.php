<?php

namespace OHMedia\CalendarBundle;

use OHMedia\CalendarBundle\DependencyInjection\Compiler\CalendarPass;
use OHMedia\CalendarBundle\Service\AbstractCalendarDataProvider;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class OHMediaCalendarBundle extends AbstractBundle
{
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
                ->enumNode('theme')
                    ->values([
                        'monarch-blue',
                        'monarch-green',
                        'monarch-purple',
                        'monarch-red',
                        'monarch-yellow',
                        'forma-blue',
                        'forma-green',
                        'forma-purple',
                        'forma-red',
                        'breezy-amber',
                        'breezy-emerald',
                        'breezy-indigo',
                        'breezy-rose',
                        'pulse-blue',
                        'pulse-green',
                        'pulse-purple',
                        'pulse-red',
                        'classic',
                    ])
                    ->defaultValue('classic')
                ->end()
            ->end()
        ;
    }

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

        $containerConfigurator->parameters()
            ->set('oh_media_calendar.theme', $config['theme'])
        ;

        $containerBuilder->registerForAutoconfiguration(AbstractCalendarDataProvider::class)
            ->addTag('oh_media_calendar.calendar_data_provider')
        ;
    }
}
