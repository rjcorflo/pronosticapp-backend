<?php

namespace App\Legacy\WebResource\Fractal\Resource;

use App\Entity\Community;
use App\Entity\Player;

/**
 * Class PlayerCommunityResource
 * @package RJ\PronosticApp\WebResource\Fractal\Resource
 */
class PlayerCommunityResource
{
    private $player;

    private $community;

    public function __construct(Player $player, Community $community)
    {
        $this->player = $player;
        $this->community = $community;
    }

    /**
     * @return Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * @return Community
     */
    public function getCommunity(): Community
    {
        return $this->community;
    }
}
