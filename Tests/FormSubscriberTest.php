<?php

/*
 * @copyright   2018 Konstantin Scheumann. All rights reserved
 * @author      Konstantin Scheumann
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticRecaptchaBundle\Tests;

use Mautic\FormBundle\Event\ValidationEvent;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use MauticPlugin\MauticRecaptchaBundle\EventListener\FormSubscriber;
use MauticPlugin\MauticRecaptchaBundle\Integration\RecaptchaIntegration;

class FormSubscriberTest extends \PHPUnit_Framework_TestCase
{

    const RECAPTCHA_TESTING_SITE_KEY = '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI';
    const RECAPTCHA_TESTING_SECRET_KEY = '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe';

    /**
     * @var RecaptchaIntegration
     */
    protected $integration;

    /**
     * @var IntegrationHelper
     */
    protected $integrationHelper;

    protected function setUp()
    {
        parent::setUp();

        $this->integration = $this->getMockBuilder(RecaptchaIntegration::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->integration
            ->method('getKeys')
            ->willReturn(['site_key' => self::RECAPTCHA_TESTING_SITE_KEY, 'secret_key' => self::RECAPTCHA_TESTING_SECRET_KEY]);


        $this->integrationHelper = $this->getMockBuilder(IntegrationHelper::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->integrationHelper
            ->method('getIntegrationObject')
            ->willReturn($this->integration);
    }

    public function testOnFormValidate()
    {
        /**
         * @param ValidationEvent $validationEvent
         */
        $validationEvent = $this->getMockBuilder(ValidationEvent::class)
            ->disableOriginalConstructor()
            ->getMock();

        $validationEvent
            ->method('getValue')
            ->willReturn('any-value-should-work');

        $validationEvent
            ->expects($this->never())
            ->method('failedValidation');

        $formSubscriber = new FormSubscriber($this->integrationHelper);
        $formSubscriber->onFormValidate($validationEvent);
    }
}
