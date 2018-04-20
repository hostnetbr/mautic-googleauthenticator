<?php

return [
    'name'        => 'Google Authenticator',
    'description' => 'Two-Factor authentication for Mautic.',
    'version'     => '2.0.2',
    'author'      => 'Henrique Rodrigues <henrique@hostnet.com.br>',
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
                    'mautic.helper.user'
                ]
            ]
        ],
        'integrations' => [
            'mautic.integration.hostnetauth' => [
                'class'     => MauticPlugin\HostnetAuthBundle\Integration\HostnetAuthIntegration::class,
                'arguments' => [
                    'mautic.helper.user'
                ],
            ]
        ]
    ]
];
