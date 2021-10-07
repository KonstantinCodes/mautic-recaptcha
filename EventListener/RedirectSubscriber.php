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

use Mautic\PageBundle\Event\RedirectResponseEvent;
use Mautic\PageBundle\PageEvents;
use MauticPlugin\MauticRecaptchaBundle\Service\RedirectLandingPage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;

class RedirectSubscriber implements EventSubscriberInterface
{
    private RedirectLandingPage $redirectLandingPage;

    public function __construct(RedirectLandingPage $redirectLandingPage)
    {
        $this->redirectLandingPage = $redirectLandingPage;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            PageEvents::ON_REDIRECT_RESPONSE => ['onRedirect', 10],
        ];
    }

    public function onRedirect(RedirectResponseEvent $redirectEvent)
    {
        if ($redirectEvent->getChannel() !== 'email') {
            return;
        }
        $redirectEvent->stopPropagation();
        $redirectEvent->setContentResponse(new Response($this->redirectLandingPage->getRecaptchaPageContent($redirectEvent->getRedirect()->getRedirectId())));
    }


}
