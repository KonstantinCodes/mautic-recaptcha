<?php

/*
 * @copyright   2018 Konstantin Scheumann. All rights reserved
 * @author      Konstantin Scheumann
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticRecaptchaBundle\Integration;

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
                    'data'=> isset($data['version']) ? $data['version'] : 'v2'
                ]
            );
        }
    }
}
