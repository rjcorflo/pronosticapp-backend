<?php

namespace AppBundle\Legacy\WebResource\Fractal\Transformer;

use AppBundle\Entity\Team;
use League\Fractal\TransformerAbstract;

/**
 * Class TeamTransformer
 */
class TeamTransformer extends TransformerAbstract
{
    /**
     * @param Team $team
     * @return array
     */
    public function transform(Team $team)
    {
        return [
            'id_equipo' => $team->getId(),
            'nombre' => $team->getName(),
            'nombre_abrev' => $team->getAlias(),
            'color_equipo' => $team->getColor(),
            'estadio' => $team->getStadium(),
            'ciudad' => $team->getCity(),
            'url' => $team->getImage()->getUrl()
        ];
    }
}
