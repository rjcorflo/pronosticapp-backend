<?php

namespace AppBundle\Legacy\WebResource\Fractal\Transformer;

use AppBundle\Entity\Matchday;
use AppBundle\Legacy\WebResource\Fractal\Resource\GeneralClassificationResource;
use Doctrine\Common\Persistence\ObjectManager;
use League\Fractal\TransformerAbstract;

/**
 * Class GeneralClassificationListTransformer
 */
class GeneralClassificationListTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'clasificacion_general'
    ];

    private $repository;

    /**
     * GeneralClassificationListTransformer constructor.
     *
     * @param ObjectManager $em
     */
    public function __construct(ObjectManager $em)
    {
        $this->repository = $em->getRepository(Matchday::class);
    }

    /**
     * @param GeneralClassificationResource $data
     * @return array
     */
    public function transform(GeneralClassificationResource $data)
    {
        return [
            'id_comunidad' => $data->getCommunity()->getId(),
            'fecha_actual' => $data->getDate()->format('Y-m-d H:i:s'),
            'id_jornada_actual' => $this->repository->getNextMatchday()->getId()
        ];
    }

    /**
     * Include general classification.
     *
     * @param GeneralClassificationResource $data
     * @return \League\Fractal\Resource\Collection
     */
    public function includeClasificacionGeneral(GeneralClassificationResource $data)
    {
        return $this->collection(
            $data->getGeneralMatchdayClassification(),
            GeneralMatchdayTransformer::class
        );
    }
}
