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
                    'mautic.recaptcha.service.recaptcha_client',
                    'mautic.lead.model.lead',
                    'translator'
                ],
            ],
        ],
        'models' => [

        ],
        'others'=>[
            'mautic.recaptcha.service.recaptcha_client' => [
                'class'     => \MauticPlugin\MauticRecaptchaBundle\Service\RecaptchaClient::class,
                'arguments' => [
                    'mautic.helper.integration',
                ],
            ],
        ],
        'integrations' => [
            'mautic.integration.recaptcha' => [
                'class'     => \MauticPlugin\MauticRecaptchaBundle\Integration\RecaptchaIntegration::class,
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
                ],
            ],
        ],
    ],
    'parameters' => [

    ],
];
