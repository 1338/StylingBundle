<?php

namespace Sulu\Bundle\StylingBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SuluStylingExtension extends Extension implements PrependExtensionInterface {
    const SYSTEM_COLLECTION_ROOT = 'styling_bundle';

    /**
     * {@inheritdoc}
     */
    public function prepend(ContainerBuilder $container) {
        if ($container->hasExtension('sulu_admin')) {
            $container->prependExtensionConfig('sulu_admin', [
                'lists' => [ 'directories' => [__DIR__ . '/../Resources/config/lists']],
                'forms' => ['directories' => [__DIR__ . '/../Resources/config/forms']],
                'resources' => [
                    'styling' => [
                        'routes' => [
                            'list' => 'sulu_styling.get_stylings',
                            'detail' => 'sulu_styling.get_styling'
                        ]
                    ]
                ],
                'field_type_options' => [
                    'selection' => [
                        'styling_selection' => [
                            'default_type' => 'list_overlay',
                            'resource_key' => 'styling',
                            'types' => [
                                'list_overlay' => [
                                    'adapter' => 'table',
                                    'list_key' => 'styling',
                                    'display_properties' => [
                                        'title'
                                    ],
                                    'icon' => 'fa-calendar',
                                    'label' => 'sulu_styling.styling',
                                    'overlay_title' => 'styling'
                                ]
                            ]
                        ]
                    ]

                ]
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container) {
        $yamlLoader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $yamlLoader->load('services.yaml');
    }
}