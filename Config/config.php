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
        'public' => [
            'mautic_recaptcha_url_redirect' => [
                'path'       => '/re/r/{redirectId}',
                'controller' => 'MauticRecaptchaBundle:Public:redirect',
            ],
            'mautic_recaptcha_url_validate' => [
                'path'       => '/re/validate/{redirectId}/{token}',
                'controller' => 'MauticRecaptchaBundle:Public:validate',
            ],
        ]
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
            'mautic.recaptcha.subscriber.redirect' => [
                'class'     => \MauticPlugin\MauticRecaptchaBundle\EventListener\RedirectSubscriber::class,
                'arguments' => [
                    'mautic.recaptcha.redirect.landing.page',
                ]
            ],
            'mautic.recaptcha.subscriber.page.builder' => [
                'class'     => \MauticPlugin\MauticRecaptchaBundle\EventListener\BuilderSubscriber::class,
                'arguments' => [
                    'translator'
                ]
            ],
            'mautic.recaptcha.subscriber.custom.content' => [
                'class'     => \MauticPlugin\MauticRecaptchaBundle\EventListener\InjectCustomContentSubscriber::class,
                'arguments' => [
                    'mautic.recaptcha.settings',
                    'mautic.recaptcha.model.redirect_log',
                    'translator'
                ]
            ],
        ],
        'models' => [
            'mautic.recaptcha.model.redirect_log' => [
                'class' => \MauticPlugin\MauticRecaptchaBundle\Model\RedirectLogModel::class,
                'arguments' => [
                    'mautic.lead.model.lead'
                ]
            ],
        ],
        'others'=>[
            'mautic.recaptcha.service.recaptcha_client' => [
                'class'     => \MauticPlugin\MauticRecaptchaBundle\Service\RecaptchaClient::class,
                'arguments' => [
                    'mautic.helper.integration',
                ],
            ],
            'mautic.recaptcha.settings' => [
                'class'     => \MauticPlugin\MauticRecaptchaBundle\Integration\RecaptchaSettings::class,
                'arguments' => [
                    'mautic.helper.integration',
                ],
            ],
            'mautic.recaptcha.redirect.landing.page' => [
                'class'     => \MauticPlugin\MauticRecaptchaBundle\Service\RedirectLandingPage::class,
                'arguments' => [
                    'mautic.recaptcha.settings',
                    'event_dispatcher',
                    'mautic.page.model.page',
                    'router',
                    'request_stack',
                    'mautic.helper.templating',
                    'mautic.recaptcha.service.recaptcha_client',
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
