<?php

/*
 * @copyright   2018 Konstantin Scheumann. All rights reserved
 * @author      Konstantin Scheumann
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticRecaptchaBundle\Service;

use GuzzleHttp\Client as GuzzleClient;
use Mautic\CoreBundle\Helper\ArrayHelper;
use Mautic\FormBundle\Entity\Field;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use MauticPlugin\MauticRecaptchaBundle\Exception\InvalidRecaptchaException;
use MauticPlugin\MauticRecaptchaBundle\Integration\RecaptchaIntegration;
use Mautic\PluginBundle\Integration\AbstractIntegration;

class RecaptchaClient
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

    private function validateRecaptchaToken(string $token): array
    {
        $client   = new GuzzleClient(['timeout' => 10]);
        $response = $client->post(
            self::VERIFY_URL,
            [
                'form_params' => [
                    'secret'   => $this->secretKey,
                    'response' => $token,
                ],
            ]
        );


        return json_decode($response->getBody(), true);
    }


    /**
     * @param string $response
     * @param Field  $field
     *
     * @return bool
     */
    public function verifyFormField($token, Field $field): bool
    {
        $scoreValidation = ArrayHelper::getValue('scoreValidation', $field->getProperties());
        $minScore = (float)  ArrayHelper::getValue('minScore', $field->getProperties());
        try {
            return $this->verify($token, $scoreValidation, $minScore);
        } catch (InvalidRecaptchaException $invalidRecaptchaException) {
            return false;
        }
    }

    public function verify($token, bool $scoreValidation = null, float $minScore = null): bool
    {
        $response = $this->validateRecaptchaToken($token);
        $score    = (float) ArrayHelper::getValue('score', $response);

        if (array_key_exists('success', $response) && $response['success'] === true) {

            if ($score && $scoreValidation && $minScore > $score) {
                return false;
            }

            return true;
        }

        if (array_key_exists('error-codes', $response) && isset($response[0])) {
            throw new InvalidRecaptchaException($response[0]);
        }

        return false;
    }

}
