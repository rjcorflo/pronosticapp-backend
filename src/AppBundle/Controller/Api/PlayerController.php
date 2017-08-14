<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Participant;
use AppBundle\Entity\Player;
use AppBundle\Legacy\Util\General\ErrorCodes;
use AppBundle\Legacy\Util\General\ResponseGenerator;
use AppBundle\Legacy\Util\Validation\Exception\ValidationException;
use AppBundle\Legacy\Util\Validation\ValidatorInterface;
use AppBundle\Legacy\WebResource\WebResourceGeneratorInterface;
use AppBundle\Repository\ParticipantRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Zend\Diactoros\Response;

/**
 * Class PlayerController.
 *
 * Expose player data.
 */
class PlayerController extends Controller
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
     * Logout for user.
     *
     * @Rest\Post("/player/logout")
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function logoutAction(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        /**
         * @var Player $player
         */
        $player = $request->getAttribute('player');

        // Prepare result
        $message = new MessageResult();

        try {
            $tokenString = $request->getHeader('X-Auth-Token');

            /** @var EntityRepository $tokenRepository */
            $tokenRepository = $this->getDoctrine()->getManager()->getRepository(Token::class);
            $token = $tokenRepository->findOneBy(['tokenString' => $tokenString[0]]);

            $this->getDoctrine()->getManager()->remove($token);
            $this->getDoctrine()->getManager()->flush();

            $message->setDescription(sprintf("Jugador %s ha hecho logout correctamente", $player->getNickname()));

            $resource = $this->resourceGenerator->createMessageResource($message);

            $response = $this->generateJsonCorrectResponse($response, $resource);
        } catch (\Exception $e) {
            $exception = new \Exception(
                sprintf(
                    "Jugador %s no ha podido hacer logout correctamente",
                    $player->getNickname()
                ),
                $e
            );

            $response = $this->generateJsonErrorResponse($response, $exception);
        }

        return $response;
    }

    /**
     * Get info about player.
     *
     * @Rest\Get("/player/info")
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function getInfoPlayerAction(
        ServerRequestInterface $request
    ): ResponseInterface {
        /**
         * @var Player $player
         */
        $player = $request->getAttribute('player');

        $resource = $this->resourceGenerator->createPlayerResource($player);

        $response = new Response();
        return $this->generateJsonCorrectResponse($response, $resource);
    }

    /**
     * List player communities.
     *
     * @Rest\Post("/player/communities")
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function getPlayerCommunitiesAction(
        ServerRequestInterface $request
    ): ResponseInterface {
        $bodyData = $request->getParsedBody();
        /**
         * @var Player $player
         */
        $player = $request->getAttribute('player');

        $response = new Response();

        try {
            /** @var ParticipantRepository $participantRepo */
            $participantRepo = $this->getDoctrine()->getRepository(Participant::class);

            $date = null;

            if (isset($bodyData['fecha']) && $bodyData['fecha'] != '') {
                $date = \DateTime::createFromFormat('Y-m-d H:i:s', $bodyData['fecha']);

                if (!$date) {
                    $exception = new ValidationException('Error validando la fecha');
                    $exception->addMessageWithCode(
                        ErrorCodes::INCORRECT_DATE,
                        'Error parseando el campo fecha recibido. Debe venir en formato dd-MM-yyyy.'
                    );
                    throw $exception;
                }
            }

            $communitiesList = $participantRepo->findCommunitiesFromPlayer($player, $date);

            $resource = $this->resourceGenerator->createCommunityListResource($player, $communitiesList);

            $response = $this->generateJsonCorrectResponse($response, $resource);
        } catch (\Exception $e) {
            $response = $this->generateJsonErrorResponse($response, $e);
        }

        return $response;
    }
}
