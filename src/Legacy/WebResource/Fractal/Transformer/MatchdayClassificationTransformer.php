<?php

namespace App\Legacy\WebResource\Fractal\Transformer;

use App\Entity\MatchdayClassification;
use League\Fractal\TransformerAbstract;

/**
 * Class MatchdayClassificationTransformer
 * @package RJ\PronosticApp\WebResource\Fractal\Transformer
 */
class MatchdayClassificationTransformer extends TransformerAbstract
{
    /**
     * @param MatchdayClassification $classification
     * @return array
     */
    public function transform(MatchdayClassification $classification)
    {
        return [
            'id_jornada' => $classification->getMatchday()->getId(),
            'id_comunidad' => $classification->getCommunity()->getId(),
            'id_jugador' => $classification->getPlayer()->getId(),
            'puesto' => $classification->getPosition(),
            'total' => $classification->getTotalPoints(),
            'aciertos_diez' => $classification->getHitsTenPoints(),
            'aciertos_cinco' => $classification->getHitsFivePoints(),
            'aciertos_tres' => $classification->getHitsThreePoints(),
            'aciertos_dos' => $classification->getHitsTwoPoints(),
            'aciertos_uno' => $classification->getHitsOnePoints(),
            'aciertos_negativo' => $classification->getHitsNegativePoints(),
            'puntos_posicion' => $classification->getPointsForPosition()
        ];
    }
}
