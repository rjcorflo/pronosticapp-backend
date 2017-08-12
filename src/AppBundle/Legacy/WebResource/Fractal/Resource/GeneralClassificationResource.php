<?php

namespace AppBundle\Legacy\WebResource\Fractal\Resource;

use AppBundle\Entity\Community;
use AppBundle\Entity\Matchday;

/**
 * Resource only for display.
 */
class GeneralClassificationResource
{
    /**
     * Actual date.
     * @var \DateTime
     */
    private $date;

    /**
     * Community.
     * @var Community
     */
    private $community;

    /**
     * @var GeneralMatchdayClassificationResource[]
     */
    private $generaMatchdayClassifications = [];

    /**
     * GeneralClassificationResource constructor.
     *
     * @param Community $community
     * @param Matchday[] $matchdays
     */
    public function __construct(Community $community, array $matchdays)
    {
        $this->date = new \DateTime();
        $this->community = $community;
        $this->setMatchdays($matchdays);
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * @return Community
     */
    public function getCommunity(): Community
    {
        return $this->community;
    }

    /**
     * @param Community $community
     */
    public function setCommunity(Community $community)
    {
        $this->community = $community;
    }

    /**
     * @param Matchday[] $matchdays
     */
    public function setMatchdays(array $matchdays)
    {
        foreach ($matchdays as $matchday) {
            $this->generaMatchdayClassifications[] = new GeneralMatchdayClassificationResource(
                $this->getCommunity(),
                $matchday
            );
        }
    }

    /**
     * @return GeneralMatchdayClassificationResource[]
     */
    public function getGeneralMatchdayClassification(): array
    {
        return $this->generaMatchdayClassifications;
    }
}
