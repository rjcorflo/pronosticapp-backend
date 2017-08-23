<?php

namespace App\Legacy\WebResource\Fractal\Transformer;

use App\Entity\Forecast;
use App\Entity\Match;
use App\Entity\Player;
use League\Fractal\TransformerAbstract;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class ForecastTransformer
 *
 * @package RJ\PronosticApp\WebResource\Fractal\Transformer
 */
class ForecastTransformer extends TransformerAbstract
{
    /**
     * @var null|\Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * ForecastTransformer constructor.
     *
     * @param RequestStack $stack
     */
    public function __construct(RequestStack $stack)
    {
        $this->request = $stack->getCurrentRequest();
    }

    /**
     * Get player from request. See {@see TokenSubscriber}.
     *
     * @return Player
     */
    private function getPlayerFromRequest()
    {
        return $this->request->attributes->get('player');
    }

    /**
     * @param Forecast $forecast
     * @return array
     */
    public function transform(Forecast $forecast)
    {
        $resource = [
            'id_pronostico' => $forecast->getId(),
            'id_partido' => $forecast->getMatch()->getId(),
            'id_jornada' => $forecast->getMatch()->getMatchday()->getId(),
            'id_comunidad' => $forecast->getCommunity()->getId(),
            'id_jugador' => $forecast->getPlayer()->getId(),
            'riesgo' => $forecast->isRisk(),
            'puntos' => $forecast->getPoints()
        ];

        if ($forecast->getPlayer() === $this->getPlayerFromRequest()) {
            $resource['goles_local'] = $forecast->getLocalGoals();
            $resource['goles_visitante'] = $forecast->getAwayGoals();
        } else {
            $resource['goles_local'] = $forecast->getMatch()->getState() === Match::STATE_NOT_PLAYED ?
                -1 : $forecast->getLocalGoals();
            $resource['goles_visitante'] = $forecast->getMatch()->getState() === Match::STATE_NOT_PLAYED ?
                -1 : $forecast->getAwayGoals();
        }

        return $resource;
    }
}
