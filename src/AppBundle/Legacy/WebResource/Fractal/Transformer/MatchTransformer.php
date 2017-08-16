<?php

namespace AppBundle\Legacy\WebResource\Fractal\Transformer;

use AppBundle\Entity\Match;
use League\Fractal\TransformerAbstract;

/**
 * Class MatchTransformer
 * @package RJ\PronosticApp\WebResource\Fractal\Transformer
 */
class MatchTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include.
     *
     * @var array
     */
    protected $defaultIncludes = [
        'equipo_local',
        'equipo_visitante'
    ];

    /**
     * @param Match $match
     * @return array
     */
    public function transform(Match $match)
    {
        $resource = [
            'id_partido' => $match->getId(),
            'id_jornada' => $match->getMatchday()->getId(),
            'id_competicion' => $match->getMatchday()->getCompetition()->getId(),
            'fecha' => strftime('%A, %d de %B de %Y', $match->getStartTime()->getTimestamp()),
            'hora' => $match->getStartTime()->format('H:i'),
            'tag' => $match->getTag(),
            'url' => $match->getImage()->getUrl(),
            'estado' => strval($match->getState())
        ];

        $resource['lugar'] = $match->getStadium() === null ? $match->getLocalTeam()->getStadium()->getName() : $match->getStadium()->getName();

        $resource['goles_local'] = $match->getState() === Match::STATE_NOT_PLAYED ?
            -1 : $match->getLocalGoals();
        $resource['goles_visitante'] = $match->getState() === Match::STATE_NOT_PLAYED ?
            -1 : $match->getAwayGoals();

        return $resource;
    }

    /**
     * Include local team.
     *
     * @param Match $match
     * @return \League\Fractal\Resource\Item
     */
    public function includeEquipoLocal(Match $match)
    {
        return $this->item($match->getLocalTeam(), TeamTransformer::class);
    }

    /**
     * Include local team.
     *
     * @param Match $match
     * @return \League\Fractal\Resource\Item
     */
    public function includeEquipoVisitante(Match $match)
    {
        return $this->item($match->getAwayTeam(), TeamTransformer::class);
    }
}
