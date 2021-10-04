<?php

/*
 * @copyright   2018 Konstantin Scheumann. All rights reserved
 * @author      Konstantin Scheumann
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticRecaptchaBundle\Tests;

use Mautic\CoreBundle\Factory\ModelFactory;
use Mautic\FormBundle\Entity\Field;
use Mautic\FormBundle\Event\ValidationEvent;
use Mautic\LeadBundle\Model\LeadModel;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use MauticPlugin\MauticRecaptchaBundle\EventListener\FormSubscriber;
use MauticPlugin\MauticRecaptchaBundle\Integration\RecaptchaIntegration;
use MauticPlugin\MauticRecaptchaBundle\Service\RecaptchaClient;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Translation\TranslatorInterface;

class IntegrationTest extends TestCase
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

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    protected function setUp(): void
    {
        parent::setUp();

        $this->integration = $this->getMockBuilder(RecaptchaIntegration::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->integration
            ->method('getKeys')
            ->willReturn(['site_key' => self::RECAPTCHA_TESTING_SITE_KEY, 'secret_key' => self::RECAPTCHA_TESTING_SECRET_KEY]);

        $this->eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->eventDispatcher
            ->method('addListener')
            ->willReturn(true);


        $this->integrationHelper = $this->getMockBuilder(IntegrationHelper::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->integrationHelper
            ->method('getIntegrationObject')
            ->willReturn($this->integration);
    }

    public function testOnFormValidate()
    {
        /** @var LeadModel $leadModel */
        $leadModel = $this->getMockBuilder(LeadModel::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var MockObject|ValidationEvent $validationEvent */
        $validationEvent = $this->getMockBuilder(ValidationEvent::class)
            ->disableOriginalConstructor()
            ->getMock();

        $translator = $this->createMock(TranslatorInterface::class);

        $validationEvent
            ->method('getValue')
            ->willReturn('any-value-should-work');
        $validationEvent
            ->expects($this->never())
            ->method('failedValidation');
        $validationEvent
            ->method('getValue')
            ->willReturn('test');
        $validationEvent
            ->method('getField')
            ->willReturn(new Field());

        $formSubscriber = new FormSubscriber(
            $this->eventDispatcher,
            $this->integrationHelper,
            new RecaptchaClient($this->integrationHelper),
            $leadModel,
            $translator
        );
        $formSubscriber->onFormValidate($validationEvent);
    }
}
