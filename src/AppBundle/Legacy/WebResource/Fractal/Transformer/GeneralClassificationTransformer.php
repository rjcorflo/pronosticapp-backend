<?php

namespace AppBundle\Legacy\WebResource\Fractal\Transformer;

use AppBundle\Entity\GeneralClassification;
use League\Fractal\TransformerAbstract;

/**
 * Class GeneralClassificationTransformer
 * @package RJ\PronosticApp\WebResource\Fractal\Transformer
 */
class GeneralClassificationTransformer extends TransformerAbstract
{
    /**
     * @param GeneralClassification $classification
     * @return array
     */
    public function transform(GeneralClassification $classification)
    {
        return [
            'id_jugador' => $classification->getPlayer()->getId(),
            'puesto' => $classification->getPosition(),
            'total' => $classification->getTotalPoints(),
            'aciertos_diez' => $classification->getHitsTenPoints(),
            'aciertos_cinco' => $classification->getHitsFivePoints(),
            'aciertos_tres' => $classification->getHitsThreePoints(),
            'aciertos_dos' => $classification->getHitsTwoPoints(),
            'aciertos_uno' => $classification->getHitsOnePoints(),
            'negativos' => $classification->getHitsNegativePoints(),
            'primero' => $classification->getTimesFirst(),
            'segundo' => $classification->getTimesSecond(),
            'tercero' => $classification->getTimesThird()
        ];
    }
}
