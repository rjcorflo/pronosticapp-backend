<?php

namespace AppBundle\Legacy\WebResource\Fractal\Transformer;

use AppBundle\Entity\GeneralClassification;
use AppBundle\Entity\Matchday;
use AppBundle\Entity\MatchdayClassification;
use AppBundle\Entity\Participant;
use AppBundle\Legacy\WebResource\Fractal\Resource\PlayerCommunityResource;
use Doctrine\ORM\EntityManager;
use League\Fractal\TransformerAbstract;
use Psr\Container\Container;

/**
 * Class PlayerCommunityTransformer
 * @package RJ\PronosticApp\WebResource\Fractal\Transformer
 */
class PlayerCommunityTransformer extends TransformerAbstract
{

    /**
     * @var ParticipantRepository
     */
    private $participantRepo;

    /**
     * @var MatchdayRepository
     */
    private $matchdayRepository;

    /**
     * @var MatchdayclassificationRepository
     */
    private $matchdayClassRepo;

    /**
     * @var GeneralclassificationRepository
     */
    private $generalClassRepo;

    /**
     * PlayerCommunityTransformer constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->participantRepo = $em->getRepository(Participant::class);
        $this->matchdayRepository = $em->getRepository(Matchday::class);
        $this->matchdayClassRepo = $em->getRepository(MatchdayClassification::class);
        $this->generalClassRepo = $em->getRepository(GeneralClassification::class);
    }

    /**
     * @param PlayerCommunityResource $playerCommunityResource
     * @return array
     */
    public function transform(PlayerCommunityResource $playerCommunityResource)
    {
        $community = $playerCommunityResource->getCommunity();

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

        /** @var Player $player */
        $player = $playerCommunityResource->getPlayer();
        $lastMatchday = $this->matchdayRepository->getLastMatchday();
        $nextMatchday = $this->matchdayRepository->getNextMatchday();
        $matchdayClassification = $this->matchdayClassRepo->findOneOrCreate($player, $community, $lastMatchday);
        $general = $this->generalClassRepo->findOneOrCreate($player, $community, $nextMatchday);

        if ($matchdayClassification->getId() != 0) {
            $resource['puntos_ultima_jornada'] = $matchdayClassification->getTotalPoints();
            $resource['puesto_ultima_jornada'] = $matchdayClassification->getPosition();
        }

        if ($general->getId() != 0) {
            $resource['puesto_general'] = $general->getPosition();
        }

        return $resource;
    }
}
