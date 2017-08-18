<?php

namespace App\Controller\Api;

use App\Controller\TokenAuthenticatedController;
use App\Entity\Community;
use App\Entity\Forecast;
use App\Entity\Match;
use App\Legacy\Model\Exception\Request\MissingParametersException;
use App\Legacy\Util\General\ErrorCodes;
use App\Legacy\Util\General\ForecastResult;
use App\Legacy\Util\General\ResponseGenerator;
use App\Legacy\Util\Validation\ValidatorInterface;
use App\Legacy\WebResource\WebResourceGeneratorInterface;
use App\Repository\CommunityRepository;
use App\Repository\ForecastRepository;
use App\Repository\MatchRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;

/**
 * Class ForecastController.
 *
 * Expose operations to update player forecast.
 *
 * @package RJ\PronosticApp\Controller
 */
class ForecastController extends FOSRestController implements TokenAuthenticatedController
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
     * Save forecasts from user.
     *
     * @Rest\Post("/community/{idCommunity}/forecast", requirements={"idCommunity": "[0-9]+"})
     *
     * @param ServerRequestInterface $request
     * @param $idCommunity
     * @return ResponseInterface
     */
    public function saveForecasts(
        ServerRequestInterface $request,
        $idCommunity
    ): ResponseInterface {
        $bodyData = $request->getParsedBody();

        $result = new ForecastResult();

        $response = new Response();

        $player = $request->getAttribute('player');

        /** @var CommunityRepository $communityRepository */
        $communityRepository = $this->getDoctrine()->getRepository(Community::class);
        $community = $communityRepository->find($idCommunity);

        /** @var ForecastRepository $forecastRepository */
        $forecastRepository = $this->getDoctrine()->getRepository(Forecast::class);

        /** @var MatchRepository $matchRepository */
        $matchRepository = $this->getDoctrine()->getRepository(Match::class);

        try {
            if (!isset($bodyData['pronosticos'])) {
                $exception = new MissingParametersException();
                $exception->addMessageWithCode(
                    ErrorCodes::MISSING_PARAMETERS,
                    'El parámetro ["pronosticos"] es obligatorio'
                );

                throw $exception;
            }

            $forecasts = $bodyData['pronosticos'];

            foreach ($forecasts as $forecastData) {
                try {
                    // Check valid data or throw exception
                    $this->checkForecastValidity($forecastData);

                    /** @var Match $match */
                    $match = $matchRepository->find($forecastData['id_partido']);

                    $actualDate = new \DateTime();
                    if ($actualDate > $match->getStartTime()) {
                        throw new \Exception('El partido y ha empezado.');
                    }

                    $result->setMatchdayId($match->getMatchday()->getId());

                    $forecast = $forecastRepository->findOneBy([
                            'player' => $player,
                            'community' => $community,
                            'match' => $match
                        ]
                    );

                    if ($forecast === null) {
                        $forecast = new Forecast();
                        $forecast->setPlayer($player);
                        $forecast->setCommunity($community);
                        $forecast->setMatch($match);
                    }

                    $forecast->setLocalGoals($forecastData['goles_local']);
                    $forecast->setAwayGoals($forecastData['goles_visitante']);
                    $forecast->setPoints(0);
                    $forecast->setRisk((bool)$forecastData['riesgo']);

                    $this->getDoctrine()->getManager()->persist($forecast);

                    $result->addCorrect(
                        $match->getId(),
                        $forecast->getLocalGoals(),
                        $forecast->getAwayGoals(),
                        $forecast->isRisk()
                    );
                } catch (\Exception $e) {
                    $result->addError($forecastData['id_partido'] ?? 0, $e->getMessage());
                }
            }

            $resource = $this->resourceGenerator->createForecastMessageResource($result);

            $response = $this->generateJsonCorrectResponse($response, $resource);
            $this->getDoctrine()->getManager()->flush();
        } catch (\Exception $e) {
            $response = $this->generateJsonErrorResponse($response, $e);
        }

        return $response;
    }

    /**
     * @param $forecast
     * @throws \Exception
     */
    private function checkForecastValidity($forecast): void
    {
        $isValid = isset($forecast['id_partido']) && isset($forecast['goles_local'])
            && isset($forecast['goles_visitante']) && isset($forecast['riesgo']);

        if (!$isValid) {
            throw new \Exception('Faltan parámetros en algún pronóstico');
        }
    }
}
