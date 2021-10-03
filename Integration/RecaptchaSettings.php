<?php

/*
 * @copyright   2021 MTCExtendee. All rights reserved
 * @author      MTCExtendee
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticRecaptchaBundle\Integration;

use Mautic\CoreBundle\Helper\ArrayHelper;
use Mautic\PluginBundle\Helper\IntegrationHelper;

class RecaptchaSettings
{
    /**
     * @var bool|\Mautic\PluginBundle\Integration\AbstractIntegration
     */
    private $integration;

    private bool $enabled = false;

    /**
     * @var array
     */
    private array $settings = [];

    private IntegrationHelper $integrationHelper;

    public function __construct(IntegrationHelper $integrationHelper)
    {
        $this->integrationHelper = $integrationHelper;
    }

    private function init()
    {
        if (!$this->integration instanceof RecaptchaIntegration) {
            $this->integration = $this->integrationHelper->getIntegrationObject(
                RecaptchaIntegration::INTEGRATION_NAME
            );
            if ($this->integration instanceof RecaptchaIntegration && $this->integration->getIntegrationSettings()->getIsPublished()) {
                $this->settings = array_merge($this->integration->getKeys(), $this->integration->mergeConfigToFeatureSettings());
                $this->enabled  = true;
            }
        }
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        $this->init();

        return $this->enabled;
    }

    public function getVersion()
    {
        $this->init();

        return ArrayHelper::getValue('version', $this->settings);
    }

    public function isSpamBotChecker(): bool
    {
        $this->init();

        return (bool) ArrayHelper::getValue(RecaptchaIntegration::SPAM_BOT_CHECKER, $this->settings);
    }


    public function getSpamBotPage(): bool
    {
        $this->init();
        return (bool) ArrayHelper::getValue(RecaptchaIntegration::SPAM_BOT_PAGE, $this->settings);
    }

    public function getSiteKey()
    {
        $this->init();
        return ArrayHelper::getValue('site_key', $this->settings);
    }
}
