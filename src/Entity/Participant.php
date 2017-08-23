<?php

namespace App\Entity;

use App\Entity\Extensions\Timestampable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Participant.
 *
 * @ORM\Entity(repositoryClass="App\Repository\ParticipantRepository")
 * @ORM\Table(name="participants")
 * @ORM\HasLifecycleCallbacks
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
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $player;

    /**
     * @var Community
     *
     * @ORM\ManyToOne(targetEntity="Community", inversedBy="participants", fetch="EAGER")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $community;

    /**
     * Set player
     *
     * @param \App\Entity\Player $player
     *
     * @return Participant
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
     * @return Participant
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
