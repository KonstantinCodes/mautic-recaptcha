<?php

declare(strict_types=1);

namespace MauticPlugin\MauticRecaptchaBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;
use Mautic\LeadBundle\Entity\Lead;
use Mautic\PageBundle\Entity\Redirect;

class RedirectLog
{
    const REDIRECT_LOGS = 'redirect_logs';

    protected int $id;


    protected Lead $lead;

    protected \DateTime $dateAdded;

    private string $redirectId;

    public function __construct()
    {
        $this->setDateAdded(new \DateTime());
    }


    public static function loadMetadata(ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable(self::REDIRECT_LOGS)
            ->setCustomRepositoryClass(RedirectLogRepository::class)
            ->addId();

        $builder->createField('redirectId', 'string')
            ->columnName('redirect_id')
            ->length(25)
            ->build();

        $builder->createManyToOne(
            'lead',
            'Mautic\LeadBundle\Entity\Lead'
        )->addJoinColumn('lead_id', 'id', true, false, 'CASCADE')->build();

        $builder->addNamedField('dateAdded', Types::DATETIME_MUTABLE, 'date_added');


    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Redirect
     */
    public function getRedirect(): Redirect
    {
        return $this->redirect;
    }

    /**
     * @param Redirect $redirect
     *
     * @return RedirectLog
     */
    public function setRedirect(Redirect $redirect): RedirectLog
    {
        $this->redirect = $redirect;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateAdded(): \DateTime
    {
        return $this->dateAdded;
    }

    /**
     * @param \DateTime $dateAdded
     *
     * @return RedirectLog
     */
    public function setDateAdded(\DateTime $dateAdded): RedirectLog
    {
        $this->dateAdded = $dateAdded;

        return $this;
    }

    /**
     * @return string
     */
    public function getRedirectId(): string
    {
        return $this->redirectId;
    }

    /**
     * @param string $redirectId
     *
     * @return RedirectLog
     */
    public function setRedirectId(string $redirectId): RedirectLog
    {
        $this->redirectId = $redirectId;

        return $this;
}

    /**
     * @return Lead
     */
    public function getLead(): Lead
    {
        return $this->lead;
    }

    /**
     * @param Lead $lead
     *
     * @return RedirectLog
     */
    public function setLead(Lead $lead): RedirectLog
    {
        $this->lead = $lead;

        return $this;
}
}
