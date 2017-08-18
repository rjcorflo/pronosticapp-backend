<?php

namespace App\Entity;

use App\Entity\Extensions\Timestampable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Matchday.
 *
 * @ORM\Entity(repositoryClass="App\Repository\MatchdayRepository")
 * @ORM\Table(name="matchdays")
 * @ORM\HasLifecycleCallbacks
 */
class Matchday
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
     * @var Competition
     *
     * @ORM\ManyToOne(targetEntity="Competition", fetch="EAGER")
     */
    private $competition;

    /**
     * @var Phase
     *
     * @ORM\ManyToOne(targetEntity="Phase", fetch="EAGER")
     */
    private $phase;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $alias;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $matchdayOrder;

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
     * Set name
     *
     * @param string $name
     *
     * @return Matchday
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set alias
     *
     * @param string $alias
     *
     * @return Matchday
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set matchdayOrder
     *
     * @param integer $matchdayOrder
     *
     * @return Matchday
     */
    public function setMatchdayOrder($matchdayOrder)
    {
        $this->matchdayOrder = $matchdayOrder;

        return $this;
    }

    /**
     * Get matchdayOrder
     *
     * @return integer
     */
    public function getMatchdayOrder()
    {
        return $this->matchdayOrder;
    }

    /**
     * Set competition
     *
     * @param \App\Entity\Competition $competition
     *
     * @return Matchday
     */
    public function setCompetition(\App\Entity\Competition $competition = null)
    {
        $this->competition = $competition;

        return $this;
    }

    /**
     * Get competition
     *
     * @return \App\Entity\Competition
     */
    public function getCompetition()
    {
        return $this->competition;
    }

    /**
     * Set phase
     *
     * @param \App\Entity\Phase $phase
     *
     * @return Matchday
     */
    public function setPhase(\App\Entity\Phase $phase = null)
    {
        $this->phase = $phase;

        return $this;
    }

    /**
     * Get phase
     *
     * @return \App\Entity\Phase
     */
    public function getPhase()
    {
        return $this->phase;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
