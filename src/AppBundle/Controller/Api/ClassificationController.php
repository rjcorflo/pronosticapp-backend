<?php

namespace AppBundle\Controller\Api;

use AppBundle\Legacy\Process\ClassificationCalculationProcess;
use AppBundle\Legacy\Util\General\MessageResult;
use AppBundle\Legacy\Util\General\ResponseGenerator;
use AppBundle\Legacy\WebResource\WebResourceGeneratorInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;

/**
 * Class ClassificationController
 */
class ClassificationController extends FOSRestController
{
    use ResponseGenerator;

    /**
     * @var WebResourceGeneratorInterface
     */
    protected $resourceGenerator;

    /**
     * @param WebResourceGeneratorInterface $resourceGenerator
     */
    public function __construct(
        WebResourceGeneratorInterface $resourceGenerator
    ) {
        $this->resourceGenerator = $resourceGenerator;
    }

    /**
     * Calculate classifications.
     *
     * @Rest\Get("/classification/calculate")
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function calculateClassifications(
        ServerRequestInterface $request
    ): ResponseInterface {
        $params = $request->getQueryParams();

        $result = new MessageResult();

        $response = new Response();

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
