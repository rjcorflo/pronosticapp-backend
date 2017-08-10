<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Extensions\Timestampable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class GeneralClassification.
 *
 * @ORM\Entity
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
}