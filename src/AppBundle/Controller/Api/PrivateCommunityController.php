<?php

namespace AppBundle\Controller\Api;

use AppBundle\Controller\TokenAuthenticatedController;
use AppBundle\Entity\Community;
use AppBundle\Entity\Participant;
use AppBundle\Entity\Player;
use AppBundle\Legacy\Model\Exception\PasswordNotMatchException;
use AppBundle\Legacy\Model\Exception\Request\MissingParametersException;
use AppBundle\Legacy\Util\General\ErrorCodes;
use AppBundle\Legacy\Util\General\MessageResult;
use AppBundle\Legacy\Util\General\ResponseGenerator;
use AppBundle\Legacy\Util\Validation\Exception\ValidationException;
use AppBundle\Legacy\Util\Validation\ValidatorInterface;
use AppBundle\Legacy\WebResource\WebResourceGeneratorInterface;
use AppBundle\Repository\CommunityRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;

/**
 * Class PrivateCommunityController.
 *
 * Expose private community data.
 */
class PrivateCommunityController extends FOSRestController implements TokenAuthenticatedController
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
     * Join to private community.
     *
     * @Rest\Post("/community/private/join")
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function joinPrivateCommunityAction(
        ServerRequestInterface $request
    ): ResponseInterface {
        $bodyData = $request->getParsedBody();

        $result = new MessageResult();

        $response = new Response();

        try {
            if (!isset($bodyData['nombre_comunidad']) || !isset($bodyData['password_comunidad'])) {
                $exception = new MissingParametersException();
                $exception->addMessageWithCode(
                    ErrorCodes::MISSING_PARAMETERS,
                    'Los parámetros ["nombre_comunidad","password_comunidad"] son obligatorios'
                );

                throw $exception;
            }

            $communityName = $bodyData['nombre_comunidad'];
            $communityPass = $bodyData['password_comunidad'];

            /** @var CommunityRepository $communityRepository */
            $communityRepository = $this->getDoctrine()->getRepository(Community::class);

            // Retrieve community
            $community = $communityRepository->findByName($communityName);

            if (!$community->isPrivate()) {
                $exception = new ValidationException('No pudo unirse a la comunidad');
                $exception->addMessageWithCode(
                    ErrorCodes::COMMUNITY_IS_NOT_PRIVATE,
                    'La comunidad no es privada'
                );
                throw $exception;
            }

            if ($communityPass !== $community->getPassword()) {
                $exception = new PasswordNotMatchException('No pudo unirse a la comunidad');
                $exception->addMessageWithCode(
                    ErrorCodes::INCORRECT_PASSWORD,
                    'Pasword incorrecta'
                );
                throw $exception;
            }

            // Proceed to add player as participant
            /** @var Player $player */
            $player = $request->getAttribute('player');

            // Check player is not already member of community
            $this->validator->existenceValidator()
                ->checkIfPlayerIsAlreadyFromCommunity($player, $community)
                ->validate();

            $participant = new Participant();
            $participant->setPlayer($player);
            $participant->setCommunity($community);

            $this->getDoctrine()->getManager()->persist($participant);
            $this->getDoctrine()->getManager()->flush();

            $result->setDescription(
                sprintf(
                    'El jugador %s se ha unido correctamente a la comunidad privada %s',
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
