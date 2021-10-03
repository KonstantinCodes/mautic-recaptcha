<?php

/*
 * @copyright   2018 Konstantin Scheumann. All rights reserved
 * @author      Konstantin Scheumann
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticRecaptchaBundle\Integration;

use Mautic\CoreBundle\Form\Type\YesNoButtonGroupType;
use Mautic\PageBundle\Form\Type\PageListType;
use Mautic\PluginBundle\Integration\AbstractIntegration;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilder;

/**
 * Class RecaptchaIntegration.
 */
class RecaptchaIntegration extends AbstractIntegration
{
    const INTEGRATION_NAME = 'Recaptcha';

    const SPAM_BOT_CHECKER = 'spam_bot_checker';

    const SPAM_BOT_PAGE    = 'spam_bot_page';

    public function getName()
    {
        return self::INTEGRATION_NAME;
    }

    public function getDisplayName()
    {
        return 'reCAPTCHA';
    }

    public function getAuthenticationType()
    {
        return 'none';
    }

    public function getRequiredKeyFields()
    {
        return [
            'site_key'   => 'mautic.integration.recaptcha.site_key',
            'secret_key' => 'mautic.integration.recaptcha.secret_key',
        ];
    }

    /**
     * @param FormBuilder|Form $builder
     * @param array            $data
     * @param string           $formArea
     */
    public function appendToForm(&$builder, $data, $formArea)
    {
        if ($formArea === 'keys') {
            $builder->add(
                'version',
                ChoiceType::class,
                [
                    'choices' => [
                        'mautic.recaptcha.v2' => 'v2',
                        'mautic.recaptcha.v3' => 'v3',
                    ],
                    'label'      => 'mautic.recaptcha.version',
                    'label_attr' => ['class' => 'control-label'],
                    'attr'       => [
                        'class'    => 'form-control',
                    ],
                    'required'    => false,
                    'placeholder' => false,
                    'data'=> $data['version'] ?? 'v2'
                ]
            );

            $builder->add(
                self::SPAM_BOT_CHECKER,
                YesNoButtonGroupType::class,
                [
                    'label'       => 'mautic.recaptcha.spam_bot_checker',
                    'label_attr'  => ['class' => 'control-label'],
                    'attr'        => [
                        'class' => 'form-control',
                        'data-show-on' => '{"integration_details_apiKeys_version":"v3"}'
                    ],
                    'required'    => false,
                    'placeholder' => false,
                    'data'        => $data[self::SPAM_BOT_CHECKER] ?? false,
                ]
            );

            $builder->add(
                self::SPAM_BOT_PAGE,
                PageListType::class,
                [
                    'label'         => 'mautic.recaptcha.spam_bot_page',
                    'label_attr'    => ['class' => 'control-label'],
                    'attr'          => [
                        'class'            => 'form-control',
                        'tooltip'          => 'mautic.recaptcha.spam_bot_page.tooltip',
                        'data-show-on' => '{"integration_details_apiKeys_spam_bot_checker_1":"checked"}'
                    ],
                    'multiple'       => false,
                    'placeholder'    => '',
                    'published_only' => true,
                ]
            );
        }
    }
}
