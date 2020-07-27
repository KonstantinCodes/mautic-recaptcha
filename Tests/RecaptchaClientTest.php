<?php

/*
 * @copyright   2018 Konstantin Scheumann. All rights reserved
 * @author      Konstantin Scheumann
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticRecaptchaBundle\Tests;

use Mautic\FormBundle\Entity\Field;
use PHPUnit_Framework_MockObject_MockBuilder;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use MauticPlugin\MauticRecaptchaBundle\Integration\RecaptchaIntegration;
use MauticPlugin\MauticRecaptchaBundle\Service\RecaptchaClient;

class RecaptchaClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PHPUnit_Framework_MockObject_MockBuilder|IntegrationHelper
     */
    private $integrationHelper;

    /**
     * @var PHPUnit_Framework_MockObject_MockBuilder|RecaptchaIntegration
     */
    private $integration;

    /**
     * @var Field
     */
    private $field;

    protected function setUp()
    {
        parent::setUp();

        $this->integrationHelper = $this->createMock(IntegrationHelper::class);
        $this->integration       = $this->createMock(RecaptchaIntegration::class);
        $this->field = new Field();
    }

    public function testVerifyWhenPluginIsNotInstalled()
    {
        $this->integrationHelper->expects($this->once())
            ->method('getIntegrationObject')
            ->willReturn(null);

        $this->integration->expects($this->never())
            ->method('getKeys');

        $this->createRecaptchaClient()->verify('', $this->field);
    }

    public function testVerifyWhenPluginIsNotConfigured()
    {
        $this->integrationHelper->expects($this->once())
            ->method('getIntegrationObject')
            ->willReturn($this->integration);

        $this->integration->expects($this->once())
            ->method('getKeys')
            ->willReturn(['site_key' => 'test', 'secret_key' => 'test']);

        $this->createRecaptchaClient()->verify('', $this->field);
    }

    /**
     * @return RecaptchaClient
     */
    private function createRecaptchaClient()
    {
        return new RecaptchaClient(
            $this->integrationHelper
        );
    }
}
