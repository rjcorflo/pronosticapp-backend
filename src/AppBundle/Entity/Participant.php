<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Extensions\Timestampable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Participant.
 *
 * @ORM\Entity
 * @ORM\Table(name="participants")
 */
class Participant
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
     * @ORM\ManyToOne(targetEntity="Player", inversedBy="participations", fetch="EAGER")
     */
    private $player;

    /**
     * @var Community
     *
     * @ORM\ManyToOne(targetEntity="Community", inversedBy="participants", fetch="EAGER")
     */
    private $community;

    /**
     * Set player
     *
     * @param \AppBundle\Entity\Player $player
     *
     * @return Participant
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
     * @return Participant
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function __toString()
    {
        return sprintf('%s - %s', $this->getPlayer()->getNickname(), $this->getCommunity()->getName());
    }
}
