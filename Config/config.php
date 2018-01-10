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
            'mautic.recaptcha.formbundle.subscriber' => [
                'class'     => \MauticPlugin\MauticRecaptchaBundle\EventListener\FormSubscriber::class,
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
