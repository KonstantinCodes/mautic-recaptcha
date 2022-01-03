<?php

/*
 * @copyright   2021 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticRecaptchaBundle\EventListener;

use Mautic\CoreBundle\CoreEvents;
use Mautic\CoreBundle\Event\CustomContentEvent;
use MauticPlugin\MauticRecaptchaBundle\Integration\RecaptchaSettings;
use MauticPlugin\MauticRecaptchaBundle\Model\RedirectLogModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Translation\TranslatorInterface;

class InjectCustomContentSubscriber implements EventSubscriberInterface
{
    private RecaptchaSettings $recaptchaSettings;

    private RedirectLogModel $redirectLogModel;

    private TranslatorInterface $translator;

    public function __construct(RecaptchaSettings $recaptchaSettings, RedirectLogModel $redirectLogModel, TranslatorInterface $translator)
    {
        $this->recaptchaSettings = $recaptchaSettings;
        $this->redirectLogModel = $redirectLogModel;
        $this->translator = $translator;
    }
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            CoreEvents::VIEW_INJECT_CUSTOM_CONTENT => ['injectViewCustomContent', 0],
        ];
    }

    public function injectViewCustomContent(CustomContentEvent $customContentEvent)
    {
        if ($this->recaptchaSettings->isEnabled() === false) {
            return;
        }

        if (($customContentEvent->getVars()['channel'] ?? null) !== 'email') {
            return;
        }

        if ($customContentEvent->getContext() === 'click_counts_headers') {
            $customContentEvent->addContent( '<td>'.$this->translator->trans('mautic.recaptcha.bot_clicks').'</td>');
            $customContentEvent->addContent( '<td>'.$this->translator->trans('mautic.recaptcha.unique_bot_clicks').'</td>');
        }

        if ($customContentEvent->getContext() === 'click_counts') {
            $logs = $this->redirectLogModel->getRepository()->getLogsRedirectId($customContentEvent->getVars()['redirect_id']) ?? [];
            $customContentEvent->addContent('<td>'.$logs['bot_hits'] ?? 0 .'</td>');
            $customContentEvent->addContent( '<td>'.$logs['unique_bot_hits'] ?? 0 .'</td>');
        }


    }


}
