<?php

namespace App\Legacy\WebResource\Fractal\Resource;

use App\Entity\Community;
use App\Entity\Forecast;
use App\Entity\Match;
use App\Entity\Matchday;
use App\Entity\MatchdayClassification;
use App\Entity\Player;

/**
 * Resource for display.
 */
class CommunityDataResource
{
    /** @var Community */
    private $community;

    /** @var \DateTime */
    private $date;

    /** @var Player[] */
    private $players;

    /** @var Matchday[] */
    private $matchdays;

    /** @var  Match[] */
    private $matches;

    /** @var  Forecast[] */
    private $forecasts;

    /** @var  MatchdayClassification[] */
    private $classification;

    /**
     * CommunityDataResource constructor.
     * @param Community $community
     */
    public function __construct(Community $community)
    {
        $this->community = $community;
        $this->date = new \DateTime();
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
     * @return Player[]
     */
    public function getPlayers(): array
    {
        return $this->players;
    }

    /**
     * @param Player[] $players
     */
    public function setPlayers(array $players)
    {
        $this->players = $players;
    }

    /**
     * @return Matchday[]
     */
    public function getMatchdays(): array
    {
        return $this->matchdays;
    }

    /**
     * @param Matchday[] $matchdays
     */
    public function setMatchdays(array $matchdays)
    {
        $this->matchdays = $matchdays;
    }

    /**
     * @return Match[]
     */
    public function getMatches(): array
    {
        return $this->matches;
    }

    /**
     * @param Match[] $matches
     */
    public function setMatches(array $matches)
    {
        $this->matches = $matches;
    }

    /**
     * @return Forecast[]
     */
    public function getForecasts(): array
    {
        return $this->forecasts;
    }

    /**
     * @param Forecast[] $forecasts
     */
    public function setForecasts(array $forecasts)
    {
        $this->forecasts = $forecasts;
    }

    /**
     * @return Matchdayclassification[]
     */
    public function getClassification(): array
    {
        return $this->classification;
    }

    /**
     * @param Matchdayclassification[] $classification
     */
    public function setClassification(array $classification)
    {
        $this->classification = $classification;
    }
}
