<?php

return [
    'name'        => 'Google Authenticator',
    'description' => 'Two-Factor authentication for Mautic.',
    'version'     => '1.1.5',
    'author'      => 'Henrique Rodrigues',
    'routes'      => [
        'main' => [
            'mautic_gauth_test' => [
                'path'       => '/gauth',
                'controller' => 'MauticAuthBundle:Auth:auth',
            ]
        ]
    ],
    'services' => [
        'events' => [
            'mautic.auth.subscriber.user_bundle' => [
                'class'     => 'MauticPlugin\MauticAuthBundle\EventListener\UserSubscriber',
                'arguments' => [
                    'router',
                    'mautic.security',
                    'mautic.helper.integration'
                ]
            ]
        ],
        'integrations' => [
            'mautic.integration.auth' => [
                'class'     => \MauticPlugin\MauticAuthBundle\Integration\AuthIntegration::class,
                'arguments' => [
                    'mautic.helper.user'
                ],
            ]
        ]
    ]
];
