<?php

/*
 * @copyright   2018 Konstantin Scheumann. All rights reserved
 * @author      Konstantin Scheumann
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticRecaptchaBundle\Service;

use Mautic\CoreBundle\Helper\TemplatingHelper;
use Mautic\PageBundle\Model\PageModel;
use MauticPlugin\MauticRecaptchaBundle\EventListener\BuilderSubscriber;
use MauticPlugin\MauticRecaptchaBundle\Integration\RecaptchaSettings;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;

class RedirectLandingPage
{
    private RecaptchaSettings $recaptchaSettings;

    private EventDispatcherInterface $dispatcher;

    private PageModel $pageModel;

    private Router $router;

    private ?Request $request;

    private TemplatingHelper $templatingHelper;

    private RecaptchaClient $recaptchaClient;

    public function __construct(
        RecaptchaSettings $recaptchaSettings,
        EventDispatcherInterface $dispatcher,
        PageModel $pageModel,
        Router $router,
        RequestStack $requestStack,
        TemplatingHelper $templatingHelper,
        RecaptchaClient $recaptchaClient
    ) {
        $this->recaptchaSettings = $recaptchaSettings;
        $this->dispatcher        = $dispatcher;
        $this->pageModel         = $pageModel;
        $this->router            = $router;
        $this->request           = $requestStack->getCurrentRequest();
        $this->templatingHelper  = $templatingHelper;
        $this->recaptchaClient   = $recaptchaClient;
    }

    public function getRecaptchaPageContent(string $redirectId)
    {
        if (!$pageId = $this->recaptchaSettings->getSpamBotPage()) {
            return;
        }
        if (!$page = $this->pageModel->getEntity($pageId)) {
            return;
        }
        $from        = [BuilderSubscriber::REDIRECT_URL, '</body>', '{pagetitle}'];
        $to          = [$this->getRedirectUrl($redirectId), $this->getRecaptchaContent($redirectId).'</body>', $page->getTitle()];

        return str_replace($from, $to, $page->getCustomHtml());
    }

    private function getRecaptchaContent(string $redirectId): string
    {
        $query = $this->request->query->all();
        return $this->templatingHelper->getTemplating()->render(
            'MauticRecaptchaBundle:Redirect:recaptcha.html.php',
            [
                'site_key'    => $this->recaptchaSettings->getSiteKey(),
                'redirectUrl' => $this->getRedirectUrl($redirectId),
                'clickThrough' => $query['ct'] ?? '',
                'redirectId' => $redirectId ,
            ]
        );
    }

    private function getRedirectUrl(string $redirectId): string
    {
        $query = $this->request->query->all();

        return $this->router->generate(
            'mautic_recaptcha_url_redirect',
            array_merge(['redirectId' => $redirectId], $query),
            RouterInterface::ABSOLUTE_URL
        );
    }
}
