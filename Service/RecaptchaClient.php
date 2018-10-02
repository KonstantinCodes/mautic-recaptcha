<?php

/*
 * @copyright   2018 Konstantin Scheumann. All rights reserved
 * @author      Konstantin Scheumann
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticRecaptchaBundle\Service;

use GuzzleHttp\Client as GuzzleClient;
use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use MauticPlugin\MauticRecaptchaBundle\Integration\RecaptchaIntegration;
use Mautic\PluginBundle\Integration\AbstractIntegration;

class RecaptchaClient extends CommonSubscriber
{
    const VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';

    /**
     * @var string
     */
    protected $siteKey;

    /**
     * @var string
     */
    protected $secretKey;

    /**
     * FormSubscriber constructor.
     *
     * @param IntegrationHelper $integrationHelper
     */
    public function __construct(IntegrationHelper $integrationHelper)
    {
        $integrationObject = $integrationHelper->getIntegrationObject(RecaptchaIntegration::INTEGRATION_NAME);

        if ($integrationObject instanceof AbstractIntegration) {
            $keys            = $integrationObject->getKeys();
            $this->siteKey   = isset($keys['site_key']) ? $keys['site_key'] : null;
            $this->secretKey = isset($keys['secret_key']) ? $keys['secret_key'] : null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [];
    }


    /**
     * @param string $response
     * @return bool
     */
    public function verify($response)
    {
        $client   = new GuzzleClient(['timeout' => 10]);
        $response = $client->post(
            self::VERIFY_URL,
            [
                'form_params' => [
                    'secret'   => $this->secretKey,
                    'response' => $response,
                ],
            ]
        );

        $response = json_decode($response->getBody(), true);
        if (array_key_exists('success', $response) && $response['success'] === true) {
            return true;
        }

        return false;
    }
}
