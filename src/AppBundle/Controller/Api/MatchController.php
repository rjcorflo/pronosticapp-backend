<?php

namespace AppBundle\Controller\Api;

use AppBundle\Controller\TokenAuthenticatedController;
use AppBundle\Entity\Match;
use AppBundle\Legacy\Model\Exception\Request\MissingParametersException;
use AppBundle\Legacy\Util\General\ErrorCodes;
use AppBundle\Legacy\Util\General\ResponseGenerator;
use AppBundle\Legacy\Util\Validation\ValidatorInterface;
use AppBundle\Legacy\WebResource\WebResourceGeneratorInterface;
use AppBundle\Repository\MatchRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;

/**
 * Class MatchController.
 *
 * @package RJ\PronosticApp\App\Controller
 */
class MatchController extends FOSRestController implements TokenAuthenticatedController
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
     * Get active matches from matchday.
     *
     * @Rest\Post("/community/{idCommunity}/matches/active", requirements={"idCommunity": "[0-9]+"})
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function getActiveMatchesAction(
        ServerRequestInterface $request,
        $idCommunity
    ): ResponseInterface {
        $bodyData = $request->getParsedBody();

        $response = new Response();

        try {
            if (!isset($bodyData['id_jornada'])) {
                $exception = new MissingParametersException();
                $exception->addMessageWithCode(
                    ErrorCodes::MISSING_PARAMETERS,
                    'El parámetro ["id_jornada"] es obligatorio'
                );

                throw $exception;
            }

            $idMatchday = $bodyData['id_jornada'];

            /** @var MatchRepository $matchRepository */
            $matchRepository = $this->getDoctrine()->getRepository(Match::class);

            $matches = $matchRepository->findActivesByMatchday((int) $idMatchday);

            $resource = $this->resourceGenerator->createActiveMatchesResource($matches);

            $response = $this->generateJsonCorrectResponse($response, $resource);
        } catch (\Exception $e) {
            $response = $this->generateJsonErrorResponse($response, $e);
        }

        return $response;
    }
}
