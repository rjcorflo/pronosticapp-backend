<?php

namespace App\Controller\Api;

use App\Controller\TokenAuthenticatedController;
use App\Entity\Participant;
use App\Entity\Player;
use App\Entity\Token;
use App\Legacy\Util\General\ErrorCodes;
use App\Legacy\Util\General\MessageResult;
use App\Legacy\Util\General\ResponseGenerator;
use App\Legacy\Util\Validation\Exception\ValidationException;
use App\Legacy\Util\Validation\ValidatorInterface;
use App\Legacy\WebResource\WebResourceGeneratorInterface;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityRepository;
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
class PlayerController extends Controller implements TokenAuthenticatedController
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
     * @return ResponseInterface
     */
    public function logoutAction(
        ServerRequestInterface $request
    ): ResponseInterface {
        /**
         * @var Player $player
         */
        $player = $request->getAttribute('player');

        // Prepare result
        $message = new MessageResult();

        $response = new Response();

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
                        'Error parseando el campo fecha recibido. Debe venir en formato yyyy-MM-dd HH:mi:ss.'
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
