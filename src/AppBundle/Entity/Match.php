<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Extensions\Timestampable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Match.
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MatchRepository")
 * @ORM\Table(name="matches")
 * @ORM\HasLifecycleCallbacks
 */
class Match
{
    use Timestampable;

    /** @var string Match not played yet. */
    const STATE_NOT_PLAYED = 0;

    /** @var string Match currently in play. */
    const STATE_PLAYING = 1;

    /** @var string Match finished. */
    const STATE_FINISHED = 2;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Matchday
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Matchday", fetch="EAGER")
     */
    private $matchday;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $startTime;

    /**
     * @var Team
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Team", fetch="EAGER")
     */
    private $localTeam;

    /**
     * @var Team
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Team", fetch="EAGER")
     */
    private $awayTeam;

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
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $tag;

    /**
     * @var Stadium
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Stadium", fetch="EAGER")
     */
    private $stadium;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $city;

    /**
     * @var Image
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Image", fetch="EAGER")
     */
    private $image;

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
     * Set startTime
     *
     * @param \DateTime $startTime
     *
     * @return Match
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get startTime
     *
     * @return \DateTime
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set localGoals
     *
     * @param integer $localGoals
     *
     * @return Match
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
     * @return Match
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
     * Set state
     *
     * @param integer $state
     *
     * @return Match
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return integer
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set tag
     *
     * @param string $tag
     *
     * @return Match
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Match
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set matchday
     *
     * @param \AppBundle\Entity\Matchday $matchday
     *
     * @return Match
     */
    public function setMatchday(\AppBundle\Entity\Matchday $matchday = null)
    {
        $this->matchday = $matchday;

        return $this;
    }

    /**
     * Get matchday
     *
     * @return \AppBundle\Entity\Matchday
     */
    public function getMatchday()
    {
        return $this->matchday;
    }

    /**
     * Set localTeam
     *
     * @param \AppBundle\Entity\Team $localTeam
     *
     * @return Match
     */
    public function setLocalTeam(\AppBundle\Entity\Team $localTeam = null)
    {
        $this->localTeam = $localTeam;

        return $this;
    }

    /**
     * Get localTeam
     *
     * @return \AppBundle\Entity\Team
     */
    public function getLocalTeam()
    {
        return $this->localTeam;
    }

    /**
     * Set awayTeam
     *
     * @param \AppBundle\Entity\Team $awayTeam
     *
     * @return Match
     */
    public function setAwayTeam(\AppBundle\Entity\Team $awayTeam = null)
    {
        $this->awayTeam = $awayTeam;

        return $this;
    }

    /**
     * Get awayTeam
     *
     * @return \AppBundle\Entity\Team
     */
    public function getAwayTeam()
    {
        return $this->awayTeam;
    }

    /**
     * Set stadium
     *
     * @param \AppBundle\Entity\Stadium $stadium
     *
     * @return Match
     */
    public function setStadium(\AppBundle\Entity\Stadium $stadium = null)
    {
        $this->stadium = $stadium;

        return $this;
    }

    /**
     * Get stadium
     *
     * @return \AppBundle\Entity\Stadium
     */
    public function getStadium()
    {
        return $this->stadium;
    }

    /**
     * Set image
     *
     * @param \AppBundle\Entity\Image $image
     *
     * @return Match
     */
    public function setImage(\AppBundle\Entity\Image $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \AppBundle\Entity\Image
     */
    public function getImage()
    {
        return $this->image;
    }

    public function __toString()
    {
        return $this->getLocalTeam()->getName() . " vs " . $this->getAwayTeam()->getName();
    }
}
