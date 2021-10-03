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

use Mautic\PageBundle\Event\PageBuilderEvent;
use Mautic\PageBundle\PageEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class BuilderSubscriber implements EventSubscriberInterface
{
    const REDIRECT_URL = '{redirect_url}';

    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            PageEvents::PAGE_ON_BUILD   => ['onPageBuild', 0],
        ];
    }

    public function onPageBuild(PageBuilderEvent $event)
    {
        $event->addTokens(
            $event->filterTokens(
                [
                    self::REDIRECT_URL => $this->translator->trans('mautic.recaptcha.redirect_url'),
                ]
            )
        );
    }
}
