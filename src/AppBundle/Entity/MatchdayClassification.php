<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Extensions\Timestampable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class MatchdayClassification
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MatchdayClassificationRepository")
 * @ORM\Table(name="matchday_classification")
 * @ORM\HasLifecycleCallbacks
 */
class MatchdayClassification
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
    private $basicPoints;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $pointsForPosition;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set basicPoints
     *
     * @param integer $basicPoints
     *
     * @return MatchdayClassification
     */
    public function setBasicPoints($basicPoints)
    {
        $this->basicPoints = $basicPoints;

        return $this;
    }

    /**
     * Get basicPoints
     *
     * @return integer
     */
    public function getBasicPoints()
    {
        return $this->basicPoints;
    }

    /**
     * Set pointsForPosition
     *
     * @param integer $pointsForPosition
     *
     * @return MatchdayClassification
     */
    public function setPointsForPosition($pointsForPosition)
    {
        $this->pointsForPosition = $pointsForPosition;

        return $this;
    }

    /**
     * Get pointsForPosition
     *
     * @return integer
     */
    public function getPointsForPosition()
    {
        return $this->pointsForPosition;
    }

    /**
     * Set totalPoints
     *
     * @param integer $totalPoints
     *
     * @return MatchdayClassification
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
     * @return MatchdayClassification
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
     * @return MatchdayClassification
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
     * @return MatchdayClassification
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
     * @return MatchdayClassification
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
     * @return MatchdayClassification
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
     * @return MatchdayClassification
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
     * @return MatchdayClassification
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
     * Set player
     *
     * @param \AppBundle\Entity\Player $player
     *
     * @return MatchdayClassification
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
     * @return MatchdayClassification
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
     * @return MatchdayClassification
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
