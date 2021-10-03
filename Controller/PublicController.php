<?php

/*
 * @copyright   2021 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticRecaptchaBundle\Controller;

use Mautic\CoreBundle\Controller\AbstractFormController;
use Mautic\CoreBundle\Exception\InvalidDecodedStringException;
use Mautic\CoreBundle\Helper\ClickthroughHelper;
use Mautic\PageBundle\Helper\RedirectHelper;
use Mautic\PageBundle\Model\RedirectModel;
use MauticPlugin\MauticRecaptchaBundle\Exception\InvalidRecaptchaException;
use MauticPlugin\MauticRecaptchaBundle\Model\RedirectLogModel;
use MauticPlugin\MauticRecaptchaBundle\Service\RecaptchaClient;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class PublicController extends AbstractFormController
{
    private RedirectHelper $redirectHelper;

    private RecaptchaClient $recaptchaClient;

    private RedirectLogModel $redirectLogModel;

    private Logger $logger;

    private RedirectModel $redirectModel;

    public function initialize(FilterControllerEvent $event)
    {
        $this->redirectHelper = $this->get('mautic.page.helper.redirect');
        $this->recaptchaClient =  $this->get('mautic.recaptcha.service.recaptcha_client');
        $this->redirectLogModel =  $this->get('mautic.recaptcha.model.redirect_log');
        $this->logger = $this->get('monolog.logger.mautic');
        $this->redirectModel = $this->get('mautic.page.model.redirect');
    }

    public function validateAction($redirectId, $token): JsonResponse
    {
        try {
            $isValid = $this->recaptchaClient->verify($token);
            $ct = $this->request->query->all()['ct'] ?? null;

            if ($isValid !== true && $ct) {
                try {
                    $clickThrough = ClickthroughHelper::decodeArrayFromUrl($ct);
                    $this->redirectLogModel->createNew($redirectId, (int) $clickThrough['lead']);
                } catch (InvalidDecodedStringException $invalidDecodedStringException) {

                }
            }
        } catch (InvalidRecaptchaException $invalidRecaptchaException) {
            $this->logger->error(
                $this->translator->trans(
                    'mautic.recaptcha.spam_bot.validation.error',
                    ['%error%' => $invalidRecaptchaException->getMessage()]
                )
            );
            $isValid = false;
        }

        $response = ['success' => $isValid];

       return new JsonResponse($response);
    }

    /**
     * @param $redirectId
     *
     * @return RedirectResponse
     *
     * @throws \Exception
     */
    public function redirectAction($redirectId): RedirectResponse
    {
        if ($redirect = $this->redirectModel->getRedirectById($redirectId)) {
            return $this->redirectHelper->internalRedirect($redirect);
        }
    }
}
