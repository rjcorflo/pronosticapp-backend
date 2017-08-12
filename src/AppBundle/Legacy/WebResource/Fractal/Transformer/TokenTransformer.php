<?php

namespace AppBundle\Legacy\WebResource\Fractal\Transformer;

use AppBundle\Entity\Token;
use League\Fractal\TransformerAbstract;

/**
 * Class TokenTransformer
 * @package RJ\PronosticApp\WebResource\Fractal\Transformer
 */
class TokenTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include.
     *
     * @var array
     */
    protected $defaultIncludes = [
        'jugador'
    ];

    /**
     * @param Token $token
     * @return array
     */
    public function transform(Token $token)
    {
        return [
            'token' => $token->getTokenString()
        ];
    }

    /**
     * Include Player.
     *
     * @param Token $token
     * @return \League\Fractal\Resource\Item
     */
    public function includeJugador(Token $token)
    {
        $player = $token->getPlayer();

        return $this->item($player, new PlayerTransformer());
    }
}
