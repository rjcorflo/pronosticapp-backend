<?php

namespace App\Legacy\WebResource\Fractal\Resource;

use App\Entity\Community;
use App\Entity\Player;

/**
 * Resource only for display.
 */
class CommunityListResource
{
    /**
     * Actual date.
     * @var \DateTime
     */
    private $date;

    /**
     * Player
     * @var Player
     */
    private $player;

    /**
     * List of communities
     * @var PlayerCommunityResource[]
     */
    private $communities = [];

    /**
     * @param Player $player
     * @param Community[] $communities
     */
    public function __construct(Player $player, array $communities)
    {
        $this->date = new \DateTime();
        $this->player = $player;

        foreach ($communities as $community) {
            $this->communities[] = new PlayerCommunityResource($player, $community);
        }
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
     * @return Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * @param Player $player
     */
    public function setPlayer(Player $player)
    {
        $this->player = $player;
    }

    /**
     * @return PlayerCommunityResource[]
     */
    public function getPlayerCommunities()
    {
        return $this->communities;
    }
}
