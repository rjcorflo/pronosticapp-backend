<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Extensions\Timestampable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class GeneralClassification.
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GeneralClassificationRepository")
 * @ORM\Table(name="general_classification")
 * @ORM\HasLifecycleCallbacks
 */
class GeneralClassification
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
     */
    private $player;

    /**
     * @var Community
     *
     * @ORM\ManyToOne(targetEntity="Community", fetch="EAGER")
     */
    private $community;

    /**
     * @var Matchday
     *
     * @ORM\ManyToOne(targetEntity="Matchday", fetch="EAGER")
     */
    private $matchday;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $totalPoints;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $hitsTenPoints;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $hitsFivePoints;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $hitsThreePoints;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $hitsTwoPoints;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $hitsOnePoints;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $hitsNegativePoints;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $position;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $timesFirst;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $timesSecond;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $timesThird;

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
     * Set totalPoints
     *
     * @param integer $totalPoints
     *
     * @return GeneralClassification
     */
    public function setTotalPoints($totalPoints)
    {
        $this->totalPoints = $totalPoints;

        return $this;
    }

    /**
     * Get totalPoints
     *
     * @return integer
     */
    public function getTotalPoints()
    {
        return $this->totalPoints;
    }

    /**
     * Set hitsTenPoints
     *
     * @param integer $hitsTenPoints
     *
     * @return GeneralClassification
     */
    public function setHitsTenPoints($hitsTenPoints)
    {
        $this->hitsTenPoints = $hitsTenPoints;

        return $this;
    }

    /**
     * Get hitsTenPoints
     *
     * @return integer
     */
    public function getHitsTenPoints()
    {
        return $this->hitsTenPoints;
    }

    /**
     * Set hitsFivePoints
     *
     * @param integer $hitsFivePoints
     *
     * @return GeneralClassification
     */
    public function setHitsFivePoints($hitsFivePoints)
    {
        $this->hitsFivePoints = $hitsFivePoints;

        return $this;
    }

    /**
     * Get hitsFivePoints
     *
     * @return integer
     */
    public function getHitsFivePoints()
    {
        return $this->hitsFivePoints;
    }

    /**
     * Set hitsThreePoints
     *
     * @param integer $hitsThreePoints
     *
     * @return GeneralClassification
     */
    public function setHitsThreePoints($hitsThreePoints)
    {
        $this->hitsThreePoints = $hitsThreePoints;

        return $this;
    }

    /**
     * Get hitsThreePoints
     *
     * @return integer
     */
    public function getHitsThreePoints()
    {
        return $this->hitsThreePoints;
    }

    /**
     * Set hitsTwoPoints
     *
     * @param integer $hitsTwoPoints
     *
     * @return GeneralClassification
     */
    public function setHitsTwoPoints($hitsTwoPoints)
    {
        $this->hitsTwoPoints = $hitsTwoPoints;

        return $this;
    }

    /**
     * Get hitsTwoPoints
     *
     * @return integer
     */
    public function getHitsTwoPoints()
    {
        return $this->hitsTwoPoints;
    }

    /**
     * Set hitsOnePoints
     *
     * @param integer $hitsOnePoints
     *
     * @return GeneralClassification
     */
    public function setHitsOnePoints($hitsOnePoints)
    {
        $this->hitsOnePoints = $hitsOnePoints;

        return $this;
    }

    /**
     * Get hitsOnePoints
     *
     * @return integer
     */
    public function getHitsOnePoints()
    {
        return $this->hitsOnePoints;
    }

    /**
     * Set hitsNegativePoints
     *
     * @param integer $hitsNegativePoints
     *
     * @return GeneralClassification
     */
    public function setHitsNegativePoints($hitsNegativePoints)
    {
        $this->hitsNegativePoints = $hitsNegativePoints;

        return $this;
    }

    /**
     * Get hitsNegativePoints
     *
     * @return integer
     */
    public function getHitsNegativePoints()
    {
        return $this->hitsNegativePoints;
    }

    /**
     * Set position
     *
     * @param integer $position
     *
     * @return GeneralClassification
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set timesFirst
     *
     * @param integer $timesFirst
     *
     * @return GeneralClassification
     */
    public function setTimesFirst($timesFirst)
    {
        $this->timesFirst = $timesFirst;

        return $this;
    }

    /**
     * Get timesFirst
     *
     * @return integer
     */
    public function getTimesFirst()
    {
        return $this->timesFirst;
    }

    /**
     * Set timesSecond
     *
     * @param integer $timesSecond
     *
     * @return GeneralClassification
     */
    public function setTimesSecond($timesSecond)
    {
        $this->timesSecond = $timesSecond;

        return $this;
    }

    /**
     * Get timesSecond
     *
     * @return integer
     */
    public function getTimesSecond()
    {
        return $this->timesSecond;
    }

    /**
     * Set timesThird
     *
     * @param integer $timesThird
     *
     * @return GeneralClassification
     */
    public function setTimesThird($timesThird)
    {
        $this->timesThird = $timesThird;

        return $this;
    }

    /**
     * Get timesThird
     *
     * @return integer
     */
    public function getTimesThird()
    {
        return $this->timesThird;
    }

    /**
     * Set player
     *
     * @param \AppBundle\Entity\Player $player
     *
     * @return GeneralClassification
     */
    public function setPlayer(\AppBundle\Entity\Player $player = null)
    {
        $this->player = $player;

        return $this;
    }

    /**
     * Get player
     *
     * @return \AppBundle\Entity\Player
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * Set community
     *
     * @param \AppBundle\Entity\Community $community
     *
     * @return GeneralClassification
     */
    public function setCommunity(\AppBundle\Entity\Community $community = null)
    {
        $this->community = $community;

        return $this;
    }

    /**
     * Get community
     *
     * @return \AppBundle\Entity\Community
     */
    public function getCommunity()
    {
        return $this->community;
    }

    /**
     * Set matchday
     *
     * @param \AppBundle\Entity\Matchday $matchday
     *
     * @return GeneralClassification
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
}
