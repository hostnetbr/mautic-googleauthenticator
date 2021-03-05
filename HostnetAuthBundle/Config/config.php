<?php

return [
    'name'        => 'Google Authenticator',
    'description' => 'Two-Factor authentication for Mautic.',
    'version'     => '3.0.0',
    'author'      => 'Hostnet Internet',
    'routes'      => [
        'main' => [
            'hostnet_google_authenticator' => [
                'path'       => '/gauth',
                'controller' => 'HostnetAuthBundle:Auth:auth',
            ]
        ]
    ],
    'services' => [
        'events' => [
            'plugin.hostnetauth.userbundle.subscriber' => [
                'class'     => MauticPlugin\HostnetAuthBundle\EventListener\UserSubscriber::class,
                'arguments' => [
                    'router',
                    'mautic.security',
                    'mautic.helper.integration',
                    'mautic.helper.user',
                    'doctrine.orm.entity_manager'
                ]
            ]
        ],
        'integrations' => [
            'mautic.integration.hostnetauth' => [
                'class'     => MauticPlugin\HostnetAuthBundle\Integration\HostnetAuthIntegration::class,
                'arguments' => [
                    'event_dispatcher',
                    'mautic.helper.cache_storage',
                    'doctrine.orm.entity_manager',
                    'session',
                    'request_stack',
                    'router',
                    'translator',
                    'logger',
                    'mautic.helper.encryption',
                    'mautic.lead.model.lead',
                    'mautic.lead.model.company',
                    'mautic.helper.paths',
                    'mautic.core.model.notification',
                    'mautic.lead.model.field',
                    'mautic.plugin.model.integration_entity',
                    'mautic.lead.model.dnc',
                    'mautic.helper.user',
                ],
            ]
        ]
    ]
];
