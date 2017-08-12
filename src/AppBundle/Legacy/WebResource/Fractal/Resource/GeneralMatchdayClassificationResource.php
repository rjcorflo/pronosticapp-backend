<?php

namespace AppBundle\Legacy\WebResource\Fractal\Resource;

use AppBundle\Entity\Community;
use AppBundle\Entity\Matchday;

/**
 * Resource only for display.
 */
class GeneralMatchdayClassificationResource
{
    /**
     * List of matches
     * @var Community
     */
    private $community;

    /**
     * @var Matchday
     */
    private $matchday;

    /**
     * GeneralClassificationResource constructor.
     * @param Community $community
     */
    public function __construct(Community $community, Matchday $matchday)
    {
        $this->community = $community;
        $this->matchday = $matchday;
    }

    /**
     * @return Community
     */
    public function getCommunity(): Community
    {
        return $this->community;
    }

    /**
     * @return Matchday
     */
    public function getMatchday(): Matchday
    {
        return $this->matchday;
    }
}
