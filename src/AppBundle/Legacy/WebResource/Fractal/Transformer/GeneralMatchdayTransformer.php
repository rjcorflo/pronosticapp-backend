<?php

namespace AppBundle\Legacy\WebResource\Fractal\Transformer;

use AppBundle\Entity\GeneralClassification;
use AppBundle\Legacy\WebResource\Fractal\Resource\GeneralMatchdayClassificationResource;
use AppBundle\Repository\GeneralClassificationRepository;
use Doctrine\Common\Persistence\ObjectManager;
use League\Fractal\TransformerAbstract;

/**
 * Class GeneralMatchdayTransformer
 */
class GeneralMatchdayTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'clasificacion'
    ];

    /**
     * @var GeneralClassificationRepository
     */
    private $generalClassRepo;

    /**
     * GeneralMatchdayTransformer constructor.
     *
     * @param ObjectManager $em
     */
    public function __construct(ObjectManager $em)
    {
        $this->generalClassRepo = $em->getRepository(GeneralClassification::class);
    }

    /**
     * @param GeneralMatchdayClassificationResource $resource
     * @return array
     */
    public function transform(GeneralMatchdayClassificationResource $resource)
    {
        return [
            'id_jornada' => $resource->getMatchday()->getId()
        ];
    }

    /**
     * Include classification.
     *
     * @param GeneralMatchdayClassificationResource $resource
     * @return \League\Fractal\Resource\Collection
     */
    public function includeClasificacion(GeneralMatchdayClassificationResource $resource)
    {
        $classifications = $this->generalClassRepo->findOrderedByMatchdayAndCommunity(
            $resource->getMatchday(),
            $resource->getCommunity()
        );

        return $this->collection($classifications, GeneralClassificationTransformer::class);
    }
}
