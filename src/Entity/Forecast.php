<?php

namespace App\Entity;

use App\Entity\Extensions\Timestampable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Forecast.
 *
 * @ORM\Entity(repositoryClass="App\Repository\ForecastRepository")
 * @ORM\Table(name="forecasts")
 * @ORM\HasLifecycleCallbacks
 */
class Forecast
{
    use Timestampable;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Player
     *
     * @ORM\ManyToOne(targetEntity="Player", fetch="EAGER")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $player;

    /**
     * @var Community
     *
     * @ORM\ManyToOne(targetEntity="Community", fetch="EAGER")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $community;

    /**
     * @var Match
     *
     * @ORM\ManyToOne(targetEntity="Match", fetch="EAGER")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $match;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $localGoals;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $awayGoals;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $risk;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $points;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set localGoals
     *
     * @param integer $localGoals
     *
     * @return Forecast
     */
    public function setLocalGoals($localGoals)
    {
        $this->localGoals = $localGoals;

        return $this;
    }

    /**
     * Get localGoals
     *
     * @return integer
     */
    public function getLocalGoals()
    {
        return $this->localGoals;
    }

    /**
     * Set awayGoals
     *
     * @param integer $awayGoals
     *
     * @return Forecast
     */
    public function setAwayGoals($awayGoals)
    {
        $this->awayGoals = $awayGoals;

        return $this;
    }

    /**
     * Get awayGoals
     *
     * @return integer
     */
    public function getAwayGoals()
    {
        return $this->awayGoals;
    }

    /**
     * Set risk
     *
     * @param boolean $risk
     *
     * @return Forecast
     */
    public function setRisk($risk)
    {
        $this->risk = $risk;

        return $this;
    }

    /**
     * Get risk
     *
     * @return boolean
     */
    public function isRisk()
    {
        return $this->risk;
    }

    /**
     * Set points
     *
     * @param integer $points
     *
     * @return Forecast
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return integer
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set player
     *
     * @param \App\Entity\Player $player
     *
     * @return Forecast
     */
    public function setPlayer(\App\Entity\Player $player = null)
    {
        $this->player = $player;

        return $this;
    }

    /**
     * Get player
     *
     * @return \App\Entity\Player
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * Set community
     *
     * @param \App\Entity\Community $community
     *
     * @return Forecast
     */
    public function setCommunity(\App\Entity\Community $community = null)
    {
        $this->community = $community;

        return $this;
    }

    /**
     * Get community
     *
     * @return \App\Entity\Community
     */
    public function getCommunity()
    {
        return $this->community;
    }

    /**
     * Set match
     *
     * @param \App\Entity\Match $match
     *
     * @return Forecast
     */
    public function setMatch(\App\Entity\Match $match = null)
    {
        $this->match = $match;

        return $this;
    }

    /**
     * Get match
     *
     * @return \App\Entity\Match
     */
    public function getMatch()
    {
        return $this->match;
    }


    /**
     * Calculate points.
     */
    public function calculateActualPoints(): void
    {
        $match = $this->getMatch();

        if ($match->getState() === Match::STATE_NOT_PLAYED) {
            $this->setPoints(0);
            return;
        } elseif ($match->getLocalGoals() == -1 && $match->getAwayGoals() == -1) {
            $this->setPoints(0);
            return;
        }

        // Calculate points
        $points = 0;

        if ($this->exactResult($match)) {
            $points = 5;
        } elseif ($this->correctResultWithSameDifferenceInGoals($match)) {
            $points = 3;
        } elseif ($this->correctResultWithOneExactResult($match)) {
            $points = 2;
        } elseif ($this->correctResult($match)) {
            $points = 1;
        }

        // Correcciones a los puntos
        if ($this->isRisk()) {
            $points = $this->exactResult($match) ? 10 : -1;
        }

        $this->setPoints($points);
    }

    /**
     * Check exact result.
     * @param Match $match
     * @return bool
     */
    private function exactResult(Match $match): bool
    {
        return $match->getLocalGoals() == $this->getLocalGoals()
            && $match->getAwayGoals() == $this->getAwayGoals();
    }

    /**
     * Check difference in goals and correct winning team.
     * @param Match $match
     * @return bool
     */
    private function correctResultWithSameDifferenceInGoals(Match $match): bool
    {
        return $match->getLocalGoals() - $match->getAwayGoals()
            == $this->getLocalGoals() - $this->getAwayGoals();
    }

    /**
     * Check
     * @param Match $match
     * @return bool
     */
    private function correctResult(Match $match): bool
    {
        return ($match->getLocalGoals() - $match->getAwayGoals()) *
            ($this->getLocalGoals() - $this->getAwayGoals()) > 0
            || ($match->getLocalGoals() - $match->getAwayGoals() == 0
                && $this->getLocalGoals() - $this->getAwayGoals() == 0);
    }

    /**
     * @param Match $match
     * @return bool
     */
    private function oneExactResult(Match $match): bool
    {
        return $match->getLocalGoals() == $this->getLocalGoals()
            || $match->getAwayGoals() == $this->getAwayGoals();
    }

    /**
     * @param Match $match
     * @return bool
     */
    private function correctResultWithOneExactResult(Match $match): bool
    {
        return $this->correctResult($match) && $this->oneExactResult($match);
    }
}
