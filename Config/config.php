<?php

return [
    'name'        => 'Google Authenticator',
    'description' => 'Two-Factor authentication for Mautic.',
    'version'     => '1.3.2',
    'author'      => 'Henrique Rodrigues',
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
            'mautic.auth.subscriber.user_bundle' => [
                'class'     => 'MauticPlugin\HostnetAuthBundle\EventListener\UserSubscriber',
                'arguments' => [
                    'router',
                    'mautic.security',
                    'mautic.helper.integration',
                    'mautic.helper.user'
                ]
            ]
        ],
        'integrations' => [
            'mautic.integration.auth' => [
                'class'     => \MauticPlugin\HostnetAuthBundle\Integration\AuthIntegration::class,
                'arguments' => [
                    'mautic.helper.user'
                ],
            ]
        ]
    ]
];
