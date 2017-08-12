<?php

namespace AppBundle\Legacy\WebResource\Fractal\Transformer;

use AppBundle\Entity\Participant;
use AppBundle\Entity\Player;
use League\Fractal\TransformerAbstract;

/**
 * Class PlayerTransformer.
 *
 * @package RJ\PronosticApp\WebResource\Fractal\Transformer
 */
class PlayerTransformer extends TransformerAbstract
{
    /**
     * List of available resources for including.
     *
     * @var array
     */
    protected $availableIncludes = [
        'comunidades'
    ];

    /**
     * @param Player $player
     * @return array
     */
    public function transform(Player $player)
    {
        $item = [
            'id' => $player->getId(),
            'nickname' => $player->getNickname(),
            'email' => $player->getEmail(),
            'nombre' => $player->getFirstName(),
            'apellidos' => $player->getLastName(),
            'icon' => $player->getAvatar(),
            'color' => $player->getColor()
        ];

        return $item;
    }

    /**
     * Include Comunidades.
     *
     * @param Player $player
     * @return \League\Fractal\Resource\Collection
     */
    public function includeComunidades(Player $player)
    {
        $communities = array_map(function (Participant $object) { return $object->getCommunity(); }, $player->getParticipations()->toArray());

        return $this->collection($communities, CommunityTransformer::class);
    }
}
