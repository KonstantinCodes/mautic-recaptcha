<?php

declare(strict_types=1);

namespace MauticPlugin\MauticRecaptchaBundle\Model;

use Mautic\CoreBundle\Model\AbstractCommonModel;
use Mautic\LeadBundle\Model\LeadModel;
use MauticPlugin\MauticRecaptchaBundle\Entity\RedirectLog;
use MauticPlugin\MauticRecaptchaBundle\Entity\RedirectLogRepository;

class RedirectLogModel  extends AbstractCommonModel
{
    private LeadModel $leadModel;

    public function __construct(LeadModel $leadModel)
    {
        $this->leadModel = $leadModel;
    }

    /**
     * Get a specific entity or generate a new one if id is empty.
     *
     * @param $id
     *
     * @return object|null
     */
    public function getEntity($id = null)
    {
        if (null !== $id) {
            $repo = $this->getRepository();
            if (method_exists($repo, 'getEntity')) {
                return $repo->getEntity($id);
            }

            return $repo->find($id);
        }

        return new RedirectLog();
    }

    /**
     * Get this model's repository.
     *
     * @return RedirectLogRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository('MauticRecaptchaBundle:RedirectLog');
    }

    public function createNew(string $redirectId, int $leadid)
    {
        $redirectLog = $this->getEntity();
        $redirectLog->setRedirectId($redirectId);
        $lead = $this->leadModel->getRepository()->getEntity($leadid);
        $redirectLog->setLead($lead);
        $this->getRepository()->saveEntity($redirectLog);
    }


}
