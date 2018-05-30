<?php

/*
 * @copyright   2018 Konstantin Scheumann. All rights reserved
 * @author      Konstantin Scheumann
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticRecaptchaBundle\EventListener;

use GuzzleHttp\Client as GuzzleClient;
use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Mautic\CoreBundle\Factory\ModelFactory;
use Mautic\FormBundle\Event\FormBuilderEvent;
use Mautic\FormBundle\Event\ValidationEvent;
use Mautic\FormBundle\FormEvents;
use Mautic\LeadBundle\Event\LeadEvent;
use Mautic\LeadBundle\LeadEvents;
use Mautic\LeadBundle\Model\LeadModel;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use MauticPlugin\MauticRecaptchaBundle\Integration\RecaptchaIntegration;
use MauticPlugin\MauticRecaptchaBundle\RecaptchaEvents;

/**
 * Class FormSubscriber.
 */
class FormSubscriber extends CommonSubscriber
{
    const MODEL_NAME_KEY_LEAD = 'lead.lead';

    /**
     * @var ModelFactory
     */
    protected $modelFactory;

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
     * @param ModelFactory $modelFactory
     */
    public function __construct(IntegrationHelper $integrationHelper, ModelFactory $modelFactory)
    {
        $this->modelFactory = $modelFactory;
        $integrationObject = $integrationHelper->getIntegrationObject(RecaptchaIntegration::INTEGRATION_NAME);

        $keys            = $integrationObject->getKeys();
        $this->siteKey   = $keys['site_key'];
        $this->secretKey = $keys['secret_key'];
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::FORM_ON_BUILD         => ['onFormBuild', 0],
            RecaptchaEvents::ON_FORM_VALIDATE => ['onFormValidate', 0],
        ];
    }

    /**
     * @param FormBuilderEvent $event
     */
    public function onFormBuild(FormBuilderEvent $event)
    {
        $event->addFormField('plugin.recaptcha', [
            'label'          => 'mautic.plugin.actions.recaptcha',
            'formType'       => 'recaptcha',
            'template'       => 'MauticRecaptchaBundle:Integration:recaptcha.html.php',
            'builderOptions' => [
                'addLeadFieldList' => false,
                'addIsRequired'    => false,
                'addDefaultValue'  => false,
                'addSaveResult'    => true,
            ],
            'site_key' => $this->siteKey,
        ]);

        $event->addValidator('plugin.recaptcha.validator', [
            'eventName' => RecaptchaEvents::ON_FORM_VALIDATE,
            'fieldType' => 'plugin.recaptcha',
        ]);
    }

    /**
     * @param ValidationEvent $event
     */
    public function onFormValidate(ValidationEvent $event)
    {
        $client   = new GuzzleClient(['timeout' => 10]);
        $response = $client->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'form_params' => [
                    'secret'   => $this->secretKey,
                    'response' => $event->getValue(),
                ],
            ]
        );

        $response = json_decode($response->getBody(), true);
        if (array_key_exists('success', $response) && $response['success'] === true) {
            return;
        }

        $event->failedValidation("reCAPTCHA wasn't successful.");
        $event->getDispatcher()->addListener(LeadEvents::LEAD_POST_SAVE, function (LeadEvent $event) {
            if ($event->isNew()){
                /** @var LeadModel $model */
                $model = $this->modelFactory->getModel(self::MODEL_NAME_KEY_LEAD);
                $model->deleteEntity($event->getLead());
            }
        }, -255);
    }
}
