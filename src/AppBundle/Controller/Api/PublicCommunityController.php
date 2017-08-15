<?php

namespace AppBundle\Controller\Api;

use AppBundle\Controller\TokenAuthenticatedController;
use AppBundle\Entity\Community;
use AppBundle\Entity\Participant;
use AppBundle\Entity\Player;
use AppBundle\Legacy\Model\Exception\Request\MissingParametersException;
use AppBundle\Legacy\Util\General\ErrorCodes;
use AppBundle\Legacy\Util\General\MessageResult;
use AppBundle\Legacy\Util\General\ResponseGenerator;
use AppBundle\Legacy\Util\Validation\ValidatorInterface;
use AppBundle\Legacy\WebResource\WebResourceGeneratorInterface;
use AppBundle\Repository\CommunityRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;

/**
 * Class PublicCommunityController.
 *
 * Expose public community data.
 */
class PublicCommunityController extends FOSRestController implements TokenAuthenticatedController
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
     * List all public communities.
     *
     * @Rest\Get("/community/public/list")
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function listPublicCommunitiesAction(
        ServerRequestInterface $request
    ): ResponseInterface {
        /** @var CommunityRepository $communityRepository */
        $communityRepository = $this->getDoctrine()->getRepository(Community::class);

        $response = new Response();

        try {
            /** @var Player $player */
            $player = $request->getAttribute('player');

            $communities = $communityRepository->getAllPublicCommunities($player);

            $resource = $this->resourceGenerator->createCommunityResource($communities);

            $response = $this->generateJsonCorrectResponse($response, $resource);
        } catch (\Exception $e) {
            $response = $this->generateJsonErrorResponse($response, $e);
        }

        return $response;
    }

    /**
     * Join to public community.
     *
     * @Rest\Post("/community/public/join")
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function joinPublicCommunityAction(
        ServerRequestInterface $request
    ): ResponseInterface {
        $bodyData = $request->getParsedBody();

        $result = new MessageResult();

        $response = new Response();

        try {
            $aleatorio = $bodyData['aleatorio'] ?? true;
            $idComunidad = $bodyData['id_comunidad'] ?? 0;

            if (!$aleatorio) {
                if ((int)$idComunidad < 1) {
                    $exception = new MissingParametersException();
                    $exception->addMessageWithCode(
                        ErrorCodes::MISSING_PARAMETERS,
                        'Si el parámetro ["aleatorio"] es false, el parámetro ["id_comunidad"] es obligatorio'
                    );

                    throw $exception;
                }
            }

            /** @var Player $player */
            $player = $request->getAttribute('player');

            /** @var CommunityRepository $communityRepository */
            $communityRepository = $this->getDoctrine()->getRepository(Community::class);

            if ($aleatorio) {
                $community = $communityRepository->getRandomCommunity($player);
            } else {
                $community = $communityRepository->find($idComunidad);
            }

            // Check player is not already member of community
            $this->validator
                ->existenceValidator()
                ->checkIfPlayerIsAlreadyFromCommunity($player, $community)
                ->validate();

            $participant = new Participant();
            $participant->setPlayer($player);
            $participant->setCommunity($community);

            $this->getDoctrine()->getManager()->persist($participant);
            $this->getDoctrine()->getManager()->flush();

            $result->setDescription(
                sprintf(
                    'El jugador %s se ha unido correctamente a la comunidad pública %s',
                    $player->getNickname(),
                    $community->getName()
                )
            );

            $resource = $this->resourceGenerator->createMessageResource($result);

            $response = $this->generateJsonCorrectResponse($response, $resource);
        } catch (\Exception $e) {
            $response = $this->generateJsonErrorResponse($response, $e);
        }

        return $response;
    }
}
