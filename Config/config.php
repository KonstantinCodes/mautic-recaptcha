<?php

/*
 * @copyright   2018 Konstantin Scheumann. All rights reserved
 * @author      Konstantin Scheumann
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

return [
    'name'        => 'reCAPTCHA',
    'description' => 'Enables reCAPTCHA integration.',
    'version'     => '1.0',
    'author'      => 'Konstantin Scheumann',

    'routes' => [

    ],

    'services' => [
        'events' => [
            'mautic.recaptcha.event_listener.form_subscriber' => [
                'class'     => \MauticPlugin\MauticRecaptchaBundle\EventListener\FormSubscriber::class,
                'arguments' => [
                    'event_dispatcher',
                    'mautic.helper.integration',
                    'mautic.model.factory',
                    'mautic.recaptcha.service.recaptcha_client'
                ],
            ],
            'mautic.recaptcha.service.recaptcha_client' => [
                'class'     => \MauticPlugin\MauticRecaptchaBundle\Service\RecaptchaClient::class,
                'arguments' => [
                    'mautic.helper.integration',
                ],
            ],
        ],
        'forms' => [
            'mautic.form.type.recaptcha' => [
                'class' => \MauticPlugin\MauticRecaptchaBundle\Form\Type\RecaptchaType::class,
                'alias' => 'recaptcha',
            ],
        ],
        'models' => [

        ],
        'integrations' => [
            'mautic.integration.recaptcha' => [
                'class'     => \MauticPlugin\MauticRecaptchaBundle\Integration\RecaptchaIntegration::class,
                'arguments' => [
                ],
            ],
        ],
    ],
    'parameters' => [

    ],
];
