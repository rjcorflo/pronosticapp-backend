<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Player;
use AppBundle\Entity\Token;
use AppBundle\Legacy\Model\Exception\PasswordNotMatchException;
use AppBundle\Legacy\Model\Exception\PersistenceException;
use AppBundle\Legacy\Model\Exception\Request\MissingParametersException;
use AppBundle\Legacy\Util\General\ErrorCodes;
use AppBundle\Legacy\Util\General\MessageResult;
use AppBundle\Legacy\Util\General\ResponseGenerator;
use AppBundle\Legacy\Util\Validation\Exception\ValidationException;
use AppBundle\Legacy\Util\Validation\ValidatorInterface;
use AppBundle\Legacy\WebResource\WebResourceGeneratorInterface;
use AppBundle\Repository\PlayerRepository;
use Doctrine\ORM\EntityRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Zend\Diactoros\Response;

/**
 * Class PlayerLoginController.
 *
 * Expose operation for player login (register, login, logout, exist).
 */
class PlayerLoginController extends Controller
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
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function registerAction(
        ServerRequestInterface $request
    ): ResponseInterface {
        $bodyData = $request->getParsedBody();

        // Prepare result
        $result = new MessageResult();

        $response = new Response();

        try {
            // Retrieve data
            $nickname = $bodyData['nickname'] ?? '';
            $email = $bodyData['email'] ?? '';
            $password = $bodyData['password'] ?? '';
            $firstName = $bodyData['nombre'] ?? '';
            $lastName = $bodyData['apellidos'] ?? '';
            $idAvatar = $bodyData['id_avatar'] ?? 1;
            $color = $bodyData['color'] ?? '#FFFFFF';

            if (!$nickname || !$email || !$password) {
                $exception = new MissingParametersException();
                $exception->addMessageWithCode(
                    ErrorCodes::MISSING_PARAMETERS,
                    'Los parámetros ["nickname", "email", "password"] son obligatorios para registrarse'
                );

                throw $exception;
            }

            // Initialize Player data
            /** @var Player $player */
            $player = new Player();
            $player->setNickname($nickname);
            $player->setEmail($email);
            $player->setPassword($password);
            $player->setFirstName($firstName);
            $player->setLastName($lastName);
            $player->setAvatar($idAvatar);
            $player->setColor($color);

            // Data validation
            $this->validator
                ->playerValidator()
                ->validatePlayerData($player)
                ->validate();

            // Validate existence
            $this->validator
                ->existenceValidator()
                ->checkIfEmailExists($player)
                ->checkIfNicknameExists($player)
                ->validate();

            $this->validator
                ->basicValidator()
                ->validateId($idAvatar)
                ->validate();

            try {
                $this->getDoctrine()->getManager()->persist($player);
                $this->getDoctrine()->getManager()->flush();
                $result->setDescription("Registro correcto");
            } catch (\Exception $e) {
                $exception = new PersistenceException('Error al almacenar el jugador');
                $exception->addDefaultMessage($e->getMessage());
                throw $exception;
            }

            $resource = $this->resourceGenerator->createMessageResource($result);

            $response = $this->generateJsonCorrectResponse($response, $resource);
        } catch (\Exception $e) {
            $response = $this->generateJsonErrorResponse($response, $e);
        }

        return $response;
    }

    /**
     * Check existence of player via mail or username.
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function existAction(
        ServerRequestInterface $request
    ): ResponseInterface {
        $bodyData = $request->getParsedBody();

        // Prepare result
        $result = new MessageResult();

        $response = new Response();

        try {
            // Retrieve data
            if (!isset($bodyData['nickname']) && !isset($bodyData['email'])) {
                $exception = new MissingParametersException();
                $exception->addMessageWithCode(
                    ErrorCodes::MISSING_PARAMETERS,
                    'Uno de los parámetros ["nickname", "email"] tiene que ser pasado para comprobar si existen'
                );

                throw $exception;
            }

            $nickname = $bodyData['nickname'] ?? '';
            $email = $bodyData['email'] ?? '';

            /** @var PlayerRepository $playerRepository */
            $playerRepository = $this->getDoctrine()->getRepository(Player::class);

            if ($nickname != '' && $playerRepository->checkNickameExists($nickname)) {
                $result->isError();
                $result->addMessageWithCode(
                    ErrorCodes::PLAYER_USERNAME_ALREADY_EXISTS,
                    'El nombre de usuario ya existe'
                );
            }

            if ($email != '' && $playerRepository->checkEmailExists($email)) {
                $result->isError();
                $result->addMessageWithCode(
                    ErrorCodes::PLAYER_EMAIL_ALREADY_EXISTS,
                    'El email ya existe'
                );
            }

            if ($result->hasError()) {
                $result->setDescription('Nombre de usuario o email existentes');

                $exception = ValidationException::createFromMessageResult($result);
                throw $exception;
            }

            $result->setDescription('Datos disponibles');

            $resource = $this->resourceGenerator->createMessageResource($result);

            $response = $this->generateJsonCorrectResponse($response, $resource);
        } catch (\Exception $e) {
            $response = $this->generateJsonErrorResponse($response, $e);
        }

        return $response;
    }

    /**
     * Login for user.
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function loginAction(
        ServerRequestInterface $request
    ): ResponseInterface {
        $bodyData = $request->getParsedBody();

        $response = new Response();

        try {
            // Retrieve data
            $playerData = $bodyData['player'] ?? '';
            $password = $bodyData['password'] ?? '';

            if (!$playerData || !$password) {
                $exception = new MissingParametersException();
                $exception->addMessageWithCode(
                    ErrorCodes::MISSING_PARAMETERS,
                    'Los parámetros ["player", "password"] son obligatorios para poder loguearse'
                );

                throw $exception;
            }

            /** @var PlayerRepository $playerRepository */
            $playerRepository = $this->getDoctrine()->getManager()->getRepository(Player::class);

            // Retrieve player
            $player = $playerRepository->findPlayerByNicknameOrEmail($playerData);

            if (!password_verify($bodyData['password'], $player->getPassword())) {
                $exception = new PasswordNotMatchException();
                $exception->addMessageWithCode(
                    ErrorCodes::INCORRECT_PASSWORD,
                    "Password incorrecta"
                );

                throw $exception;
            }

            // Correct login
            $token = new Token();
            $token->generateRandomToken();
            $token->setPlayer($player);

            $this->getDoctrine()->getManager()->persist($token);
            $this->getDoctrine()->getManager()->flush();

            $resource = $this->resourceGenerator->createTokenResource($token);

            $response = $this->generateJsonCorrectResponse($response, $resource);
        } catch (\Exception $e) {
            $response = $this->generateJsonErrorResponse($response, $e);
        }

        return $response;
    }

    /**
     * Logout for user.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function logout(
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
}
