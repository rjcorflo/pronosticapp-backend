<?php

namespace AppBundle\Controller\Api;

use AppBundle\Legacy\Process\ClassificationCalculationProcess;
use AppBundle\Legacy\Util\General\MessageResult;
use FOS\RestBundle\Controller\FOSRestController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class ClassificationController
 *
 * @package RJ\PronosticApp\App\Controller
 */
class ClassificationController extends FOSRestController
{
    /**
     * Calculate classifications.
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function calculateClassifications(
        ServerRequestInterface $request
    ): ResponseInterface {
        $params = $request->getQueryParams();

        $result = new MessageResult();
        try {
            $result->setDescription('Clasificaciones calculadas');

            $process = new ClassificationCalculationProcess($this->getDoctrine()->getManager());
            $process->launch();

            $resource = $this->resourceGenerator->createMessageResource($result);

            $response = $this->generateJsonCorrectResponse($response, $resource);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            error_log($e->getTraceAsString());
            $response = $this->generateJsonErrorResponse($response, $e);
        }

        return $response;
    }
}
