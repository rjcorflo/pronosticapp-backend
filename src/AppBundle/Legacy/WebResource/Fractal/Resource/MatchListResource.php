<?php

namespace AppBundle\Legacy\WebResource\Fractal\Resource;

use AppBundle\Entity\Match;
use AppBundle\Entity\Matchday;

/**
 * Resource only for display.
 */
class MatchListResource
{
    /**
     * Actual date.
     * @var \DateTime
     */
    private $date;

    /**
     * List of matches
     * @var Matchday[]
     */
    private $matches;

    /**
     * @param Match[] $matches
     */
    public function __construct(array $matches)
    {
        $this->date = new \DateTime();
        $this->matches = $matches;
    }

    /**
     * @param \DateTime $date Actual date.
     */
    public function setActualDate(\DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * @return \DateTime
     */
    public function getActualDate()
    {
        return $this->date;
    }

    /**
     * @param Match[] $matches
     */
    public function setMatches(array $matches)
    {
        $this->matches = $matches;
    }

    /**
     * @return Match[]
     */
    public function getMatches()
    {
        return $this->matches;
    }
}
