<?php

declare(strict_types=1);

namespace MauticPlugin\MauticRecaptchaBundle\Entity;

use Mautic\CoreBundle\Entity\CommonRepository;

class RedirectLogRepository extends CommonRepository
{

    public function getLogsRedirectId(string $redirectId)
    {
        $q = $this->getEntityManager()->getConnection()->createQueryBuilder();
        return $q->select('rl.redirect_id,COUNT(rl.id) bot_hits, COUNT(DISTINCT rl.lead_id) as unique_bot_hits')
            ->from(MAUTIC_TABLE_PREFIX.RedirectLog::REDIRECT_LOGS, 'rl')
            ->where(
                $q->expr()->and(
                    $q->expr()->eq('rl.redirect_id', ':redirect_id'),
                )
            )
            ->setParameter('redirect_id', $redirectId)
            ->execute()
            ->fetch();
    }

    /**
     * @return string
     */
    public function getTableAlias()
    {
        return 'rl';
    }
}
