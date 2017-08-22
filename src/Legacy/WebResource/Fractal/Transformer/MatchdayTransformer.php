<?php

namespace App\Legacy\WebResource\Fractal\Transformer;

use App\Entity\Matchday;
use League\Fractal\TransformerAbstract;
use Psr\Container\ContainerInterface;

/**
 * Class MatchdayTransformer
 * @package RJ\PronosticApp\WebResource\Fractal\Transformer
 */
class MatchdayTransformer extends TransformerAbstract
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * TokenTransformer constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param Matchday $matchday
     * @return array
     */
    public function transform(Matchday $matchday)
    {
        return [
            'id_jornada' => $matchday->getId(),
            'id_competicion' => $matchday->getCompetition()->getId(),
            'competicion' => $matchday->getCompetition()->getName(),
            'competicion_abrev' => $matchday->getCompetition()->getAlias(),
            'fase' => $matchday->getPhase()->getName(),
            'fase_abrev' => $matchday->getPhase()->getAlias(),
            'nombre' => $matchday->getName(),
            'nombre_abrev' => $matchday->getAlias(),
            'factor' => $matchday->getPhase()->getMultiplierFactor(),
            'orden' => $matchday->getMatchdayOrder()
        ];
    }
}
