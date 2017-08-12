<?php

namespace AppBundle\Legacy\WebResource\Fractal\Transformer;

use AppBundle\Entity\Matchday;
use AppBundle\Legacy\WebResource\Fractal\Resource\CommunityDataResource;
use AppBundle\Repository\MatchdayRepository;
use Doctrine\ORM\EntityManager;
use League\Fractal\TransformerAbstract;

/**
 * Class CommunityDataTransformer.
 */
class CommunityDataTransformer extends TransformerAbstract
{
    /**
     * List of available resources for including.
     *
     * @var array
     */
    protected $defaultIncludes = [
        'participantes',
        'jornadas',
        'partidos',
        'pronosticos',
        'clasificacion'
    ];

    /** @var MatchdayRepository */
    private $matchdayRepository;

    /**
     * CommunityTransformer constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->matchdayRepository = $em->getRepository(Matchday::class);
    }

    /**
     * @param CommunityDataResource $dataResource
     * @return array
     */
    public function transform(CommunityDataResource $dataResource)
    {
        $resource = [
            'fecha_actual' => $dataResource->getDate()->format('Y-m-d H:i:s')
        ];

        $matchday = $this->matchdayRepository->getNextMatchday();

        if ($matchday === null) {
            $resource['id_jornada_actual'] = 0;
        } else {
            $resource['id_jornada_actual'] = $matchday->getId();
        }

        return $resource;
    }

    /**
     * Include Players.
     *
     * @param CommunityDataResource $dataResource
     * @return \League\Fractal\Resource\Collection
     */
    public function includeParticipantes(CommunityDataResource $dataResource)
    {
        return $this->collection($dataResource->getPlayers(), PlayerTransformer::class);
    }

    /**
     * @param CommunityDataResource $dataResource
     * @return \League\Fractal\Resource\Collection
     */
    public function includeJornadas(CommunityDataResource $dataResource)
    {
        return $this->collection($dataResource->getMatchdays(), MatchdayTransformer::class);
    }

    /**
     * @param CommunityDataResource $dataResource
     * @return \League\Fractal\Resource\Collection
     */
    public function includePartidos(CommunityDataResource $dataResource)
    {
        return $this->collection($dataResource->getMatches(), MatchTransformer::class);
    }

    /**
     * @param CommunityDataResource $dataResource
     * @return \League\Fractal\Resource\Collection
     */
    public function includePronosticos(CommunityDataResource $dataResource)
    {
        return $this->collection($dataResource->getForecasts(), ForecastTransformer::class);
    }

    /**
     * @param CommunityDataResource $dataResource
     * @return \League\Fractal\Resource\Collection
     */
    public function includeClasificacion(CommunityDataResource $dataResource)
    {
        return $this->collection(
            $dataResource->getClassification(),
            MatchdayClassificationTransformer::class
        );
    }
}
