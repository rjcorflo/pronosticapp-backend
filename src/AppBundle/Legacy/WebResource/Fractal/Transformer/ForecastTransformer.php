<?php

namespace AppBundle\Legacy\WebResource\Fractal\Transformer;

use AppBundle\Entity\Forecast;
use AppBundle\Entity\Match;
use League\Fractal\TransformerAbstract;

/**
 * Class ForecastTransformer
 *
 * @package RJ\PronosticApp\WebResource\Fractal\Transformer
 */
class ForecastTransformer extends TransformerAbstract
{
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

        $resource['goles_local'] = $forecast->getMatch()->getState() === Match::STATE_NOT_PLAYED ?
            -1 : $forecast->getLocalGoals();
        $resource['goles_visitante'] = $forecast->getMatch()->getState() === Match::STATE_NOT_PLAYED ?
            -1 : $forecast->getAwayGoals();

        return $resource;
    }
}
