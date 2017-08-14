<?php

namespace AppBundle\Controller\Api;

use AppBundle\Legacy\Util\General\ResponseGenerator;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response;

/**
 * Class UtilController.
 *
 * @package RJ\PronosticApp\App\Controller
 */
class UtilController extends FOSRestController
{
    use ResponseGenerator;

    /**
     * Get date from server.
     *
     * @Rest\Route("/util/date", methods={"GET","POST"})
     *
     * @return ResponseInterface
     */
    public function serverDateAction(): ResponseInterface {
        $date = new \DateTime();

        $resource = [
            'fecha_actual' => $date->format('Y-m-d H:i:s')
        ];

        $response = new Response();

        $response = $this->generateJsonCorrectResponse($response, json_encode($resource));

        return $response;
    }
}
