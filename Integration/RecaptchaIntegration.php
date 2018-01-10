<?php

/*
 * @copyright   2018 Konstantin Scheumann. All rights reserved
 * @author      Konstantin Scheumann
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticRecaptchaBundle\Integration;

use Mautic\PluginBundle\Integration\AbstractIntegration;

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
}
