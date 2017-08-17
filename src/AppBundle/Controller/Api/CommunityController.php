<?php

namespace AppBundle\Controller\Api;

use AppBundle\Controller\TokenAuthenticatedController;
use AppBundle\Entity\Community;
use AppBundle\Entity\Forecast;
use AppBundle\Entity\GeneralClassification;
use AppBundle\Entity\Image;
use AppBundle\Entity\Match;
use AppBundle\Entity\Matchday;
use AppBundle\Entity\MatchdayClassification;
use AppBundle\Entity\Participant;
use AppBundle\Entity\Player;
use AppBundle\Legacy\Model\Exception\Request\MissingParametersException;
use AppBundle\Legacy\Util\General\ErrorCodes;
use AppBundle\Legacy\Util\General\MessageResult;
use AppBundle\Legacy\Util\General\ResponseGenerator;
use AppBundle\Legacy\Util\Validation\Exception\ValidationException;
use AppBundle\Legacy\Util\Validation\ValidatorInterface;
use AppBundle\Legacy\WebResource\WebResourceGeneratorInterface;
use AppBundle\Repository\CommunityRepository;
use AppBundle\Repository\ForecastRepository;
use AppBundle\Repository\GeneralClassificationRepository;
use AppBundle\Repository\MatchdayClassificationRepository;
use AppBundle\Repository\MatchdayRepository;
use AppBundle\Repository\MatchRepository;
use AppBundle\Repository\ParticipantRepository;
use Doctrine\ORM\EntityRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;

/**
 * Class CommunityController.
 *
 * Operations over communities.
 */
class CommunityController extends FOSRestController implements TokenAuthenticatedController
{
    use ResponseGenerator;

    /**
     * @var WebResourceGeneratorInterface
     */
    protected $resourceGenerator;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @param WebResourceGeneratorInterface $resourceGenerator
     * @param ValidatorInterface $validator
     */
    public function __construct(
        WebResourceGeneratorInterface $resourceGenerator,
        ValidatorInterface $validator
    ) {
        $this->resourceGenerator = $resourceGenerator;
        $this->validator = $validator;
    }

    /**
     * Create community method.
     *
     * @Rest\Post("/community/create")
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function createCommunityAction(
        ServerRequestInterface $request
    ) {
        $bodyData = $request->getParsedBody();

        /** @var Player $player */
        $player = $request->getAttribute('player');

        $response = new Response();

        try {
            // Retrieve data
            $name = $bodyData['nombre'] ?? '';
            $private = $bodyData['privada'] ?? 0;
            $password = $bodyData['password'] ?? '';
            $idImage = $bodyData['id_imagen'] ?? 1;

            if (!$name) {
                $exception = new MissingParametersException();
                $exception->addMessageWithCode(
                    ErrorCodes::MISSING_PARAMETERS,
                    'El parámetro ["nombre"] es obligatorio'
                );

                throw $exception;
            }

            $community = new Community();
            $community->setName($name);
            $community->setPrivate((bool)$private);
            $community->setPassword($password);

            $this->validator->communityValidator()->validateCommunityData($community)->validate();

            $this->validator->existenceValidator()->checkIfNameExists($community)->validate();

            $this->validator->basicValidator()->validateId($idImage)->validate();

            /** @var EntityRepository $imageRepository */
            $imageRepository = $this->getDoctrine()->getRepository(Image::class);

            /** @var Image $image */
            $image = $imageRepository->find($idImage);

            // If image is not find, set standard image
            if ($image === null) {
                $image = $imageRepository->find(1);
                $community->setImage($image);
            } else {
                $community->setImage($image);
            }

            // Prepare to store community
            $this->getDoctrine()->getManager()->persist($community);

            // Add player that create community as first participant
            $participant = new Participant();
            $participant->setCommunity($community);
            $participant->setPlayer($player);

            $this->getDoctrine()->getManager()->persist($participant);
            $this->getDoctrine()->getManager()->flush();

            $resource = $this->resourceGenerator->createCommunityResource($community);

            $response = $this->generateJsonCorrectResponse($response, $resource);
        } catch (\Exception $e) {
            $response = $this->generateJsonErrorResponse($response, $e);
        }

        return $response;
    }

    /**
     * Get players from community.
     *
     * @Rest\Get("/community/{idCommunity}/players", requirements={"idCommunity": "[0-9]+"})
     *
     * @param int $idCommunity
     * @return ResponseInterface
     */
    public function getCommunityPlayersAction(
        $idCommunity
    ) {
        /** @var CommunityRepository$communityRepository */
        $communityRepository = $this->getDoctrine()->getRepository(Community::class);

        $response = new Response();

        try {
            /** @var Community $community */
            $community = $communityRepository->find($idCommunity);

            $players = $community->getParticipants()->map(function (Participant $participant) {
                return $participant->getPlayer();
            })->toArray();

            $resource = $this->resourceGenerator->exclude('comunidades.jugadores')->createPlayerResource($players);

            $response = $this->generateJsonCorrectResponse($response, $resource);
        } catch (\Exception $e) {
            $response = $this->generateJsonErrorResponse($response, $e);
        }

        return $response;
    }

    /**
     * Check existence of community name.
     *
     * @Rest\Post("/community/exist")
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function existCommunityAction(
        ServerRequestInterface $request
    ) {
        $bodyData = $request->getParsedBody();

        $result = new MessageResult();

        $response = new Response();

        try {
            if (!isset($bodyData['nombre'])) {
                $exception = new MissingParametersException();
                $exception->addMessageWithCode(
                    ErrorCodes::MISSING_PARAMETERS,
                    'El parámetro ["nombre"] es obligatorio'
                );

                throw $exception;
            }

            $communityName = $bodyData['nombre'];

            /** @var CommunityRepository $communityRepository */
            $communityRepository = $this->getDoctrine()->getRepository(Community::class);

            $nameExists = $communityRepository->checkIfNameExists($communityName);

            if ($nameExists) {
                $exception = new ValidationException('El registro ya existe');
                $exception->addMessageWithCode(
                    ErrorCodes::COMMUNITY_NAME_ALREADY_EXISTS,
                    'El nombre de la comunidad ya existe'
                );

                throw $exception;
            }

            $result->setDescription('Ese nombre de la comunidad está disponible');

            $resource = $this->resourceGenerator->createMessageResource($result);

            $response = $this->generateJsonCorrectResponse($response, $resource);
        } catch (\Exception $e) {
            $response = $this->generateJsonErrorResponse($response, $e);
        }

        return $response;
    }

    /**
     * Get data from community.
     *
     * @Rest\Post("/community/{idCommunity}/data", requirements={"idCommunity": "[0-9]+"})
     *
     * @param ServerRequestInterface $request
     * @param int $idCommunity
     * @return ResponseInterface
     */
    public function getCommunityDataAction(
        ServerRequestInterface $request,
        $idCommunity
    ) {
        $bodyData = $request->getParsedBody();

        $response = new Response();

        /** @var CommunityRepository $communityRepository */
        $communityRepository = $this->getDoctrine()->getRepository(Community::class);

        /** @var ParticipantRepository $participantRepository */
        $participantRepository = $this->getDoctrine()->getRepository(Participant::class);

        /** @var MatchdayRepository $matchdayRepository */
        $matchdayRepository = $this->getDoctrine()->getRepository(Matchday::class);

        /** @var MatchRepository $matchRepository */
        $matchRepository = $this->getDoctrine()->getRepository(Match::class);

        /** @var MatchdayClassificationRepository $matchdayClassRepo */
        $matchdayClassRepo = $this->getDoctrine()->getRepository(MatchdayClassification::class);

        /** @var ForecastRepository $forecastRepository */
        $forecastRepository = $this->getDoctrine()->getRepository(Forecast::class);

        try {
            /** @var Community $community */
            $community = $communityRepository->find($idCommunity);

            // Get players participants
            $dateUpdateParticipants = null;
            if (isset($bodyData['ultima_actualizacion_participantes'])
                && $bodyData['ultima_actualizacion_participantes'] != ''
            ) {
                $dateUpdateParticipants = \DateTime::createFromFormat(
                    'Y-m-d H:i:s',
                    $bodyData['ultima_actualizacion_participantes']
                );
            }

            $players = $participantRepository->findPlayersFromCommunity($community, $dateUpdateParticipants);


            // Get matchdays
            $dateUpdateMatchdays = null;
            if (isset($bodyData['ultima_actualizacion_jornadas'])
                && $bodyData['ultima_actualizacion_jornadas'] != ''
            ) {
                $dateUpdateMatchdays = \DateTime::createFromFormat(
                    'Y-m-d H:i:s',
                    $bodyData['ultima_actualizacion_jornadas']
                );
            }

            $matchdays = $matchdayRepository->findByCommunity($community, $dateUpdateMatchdays);


            // Get matches
            $dateUpdateMatches = null;
            if (isset($bodyData['ultima_actualizacion_partidos'])
                && $bodyData['ultima_actualizacion_partidos'] != ''
            ) {
                $dateUpdateMatches = \DateTime::createFromFormat(
                    'Y-m-d H:i:s',
                    $bodyData['ultima_actualizacion_partidos']
                );
            }

            $matches = $matchRepository->findByCommunity($community, $dateUpdateMatches);


            // Get forecasts
            $dateUpdateForecast = null;
            if (isset($bodyData['ultima_actualizacion_pronosticos'])
                && $bodyData['ultima_actualizacion_pronosticos'] != ''
            ) {
                $dateUpdateForecast = \DateTime::createFromFormat(
                    'Y-m-d H:i:s',
                    $bodyData['ultima_actualizacion_pronosticos']
                );
            }

            $forecasts = $forecastRepository->findByCommunity($community, $dateUpdateForecast);


            // Get matchdays classification
            $dateUpdateMatchdayClassification = null;
            if (isset($bodyData['ultima_actualizacion_clasificacion'])
                && $bodyData['ultima_actualizacion_clasificacion'] != ''
            ) {
                $dateUpdateMatchdayClassification = \DateTime::createFromFormat(
                    'Y-m-d H:i:s',
                    $bodyData['ultima_actualizacion_clasificacion']
                );
            }

            $classifications = $matchdayClassRepo->findByCommunityUntilNextMatchdayModifiedAfterDate(
                $community,
                $matchdayRepository->getNextMatchday(),
                $dateUpdateMatchdayClassification
            );

            // Create resource
            $resource = $this->resourceGenerator->createCommunityDataResource(
                $community,
                $players,
                $matchdays,
                $matches,
                $forecasts,
                $classifications
            );

            $response = $this->generateJsonCorrectResponse($response, $resource);
        } catch (\Exception $e) {
            $response = $this->generateJsonErrorResponse($response, $e);
        }

        return $response;
    }

    /**
     * Get general classification from community.
     *
     * @Rest\Post("/community/{idCommunity}/general", requirements={"idCommunity": "[0-9]+"})
     *
     * @param ServerRequestInterface $request
     * @param int $idCommunity
     * @return ResponseInterface
     */
    public function getCommunityGeneralClassificationAction(
        ServerRequestInterface $request,
        $idCommunity
    ) {
        $bodyData = $request->getParsedBody();

        $response = new Response();

        try {
            /** @var CommunityRepository $communityRepository */
            $communityRepository = $this->getDoctrine()->getRepository(Community::class);

            /** @var GeneralClassificationRepository $generalClassificationRepo */
            $generalClassificationRepo = $this->getDoctrine()
                ->getRepository(GeneralClassification::class);

            /** @var Community $community */
            $community = $communityRepository->find($idCommunity);

            // Get players participants
            $dateUpdate = null;
            if (isset($bodyData['ultima_actualizacion'])
                && $bodyData['ultima_actualizacion'] != '') {
                $dateUpdate = \DateTime::createFromFormat(
                    'Y-m-d H:i:s',
                    $bodyData['ultima_actualizacion']
                );
            }

            $matchdays = $generalClassificationRepo->findMatchdaysWithGeneralClassificationUpdatedAfterDate(
                $community,
                $dateUpdate
            );

            $resource = $this->resourceGenerator->createGeneralClassificationCommunityResource(
                $community,
                $matchdays
            );

            $response = $this->generateJsonCorrectResponse($response, $resource);
        } catch (\Exception $e) {
            $response = $this->generateJsonErrorResponse($response, $e);
        }

        return $response;
    }
}
