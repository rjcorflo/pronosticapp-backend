<?php

namespace AppBundle\Legacy\WebResource\Fractal\Transformer;

use AppBundle\Entity\Community;
use AppBundle\Entity\Participant;
use AppBundle\Repository\ParticipantRepository;
use Doctrine\Common\Persistence\ObjectManager;
use League\Fractal\TransformerAbstract;

/**
 * Class CommunityTransformer.
 */
class CommunityTransformer extends TransformerAbstract
{
    /**
     * List of available resources for including.
     *
     * @var array
     */
    protected $availableIncludes = [
        'jugadores'
    ];

    /**
     * @var ParticipantRepository
     */
    private $participantRepo;
    /**
     * CommunityTransformer constructor.
     *
     * @param ObjectManager $em
     */
    public function __construct(ObjectManager $em)
    {
        $this->participantRepo = $em->getRepository(Participant::class);
    }

    /**
     * @param Community $community
     * @return array
     */
    public function transform(Community $community)
    {
        $resource = [
            'id' => $community->getId(),
            'nombre' => $community->getName(),
            'password' => $community->getPassword(),
            'privada' => $community->isPrivate(),
            'id_imagen' => $community->getImage()->getId(),
            'url' => $community->getImage()->getUrl(),
            'fecha_creacion' => $community->getCreated()->format('d-m-Y'),
            'numero_jugadores' => $this->participantRepo->countPlayersFromCommunity($community),
            'puntos_ultima_jornada' => 0,
            'puesto_ultima_jornada' => 0,
            'puesto_general' => 0
        ];

        return $resource;
    }

    /**
     * Include Players.
     *
     * @param Community $community
     * @return \League\Fractal\Resource\Collection
     */
    public function includeJugadores(Community $community)
    {
        $players = array_map(function (Participant $object) {
            return $object->getCommunity();
        }, $community->getParticipants()->toArray());

        return $this->collection($players, PlayerTransformer::class);
    }
}
