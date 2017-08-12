<?php

namespace AppBundle\Legacy\Util\General;

use Psr\Http\Message\ResponseInterface;
use AppBundle\Legacy\Model\Exception\PronosticAppException;

/**
 * Trait ResponseGenerator
 *
 * Generate common responses for certain cases.
 *
 * @package RJ\PronosticApp\Util\General
 */
trait ResponseGenerator
{
    /**
     * Generate correct response.
     *
     * @param ResponseInterface $response
     * @param string $resource
     * @param string $contentType
     * @return ResponseInterface
     */
    public function generateCorrectResponse(
        ResponseInterface $response,
        string $resource,
        string $contentType
    ): ResponseInterface {
        // Prepare new response
        $newResponse = $response->withHeader(
            'Content-Type',
            $contentType
        );
        $newResponse->getBody()->write($resource);
        return $newResponse;
    }

    /**
     * Generate correct response in JSON.
     *
     * @param ResponseInterface $response
     * @param string $resource
     * @return ResponseInterface
     */
    public function generateJsonCorrectResponse(
        ResponseInterface $response,
        string $resource
    ): ResponseInterface {
        return $this->generateCorrectResponse($response, $resource, 'application/json');
    }

    /**
     * Generate error response from exception.
     *
     * @param ResponseInterface $response
     * @param \Exception $exception
     * @param string $contentType
     * @return ResponseInterface
     */
    public function generateErrorResponse(
        ResponseInterface $response,
        \Exception $exception,
        string $contentType
    ): ResponseInterface {
        if ($exception instanceof PronosticAppException) {
            $response = $this->generateSpecificErrorResponse($response, $exception, $contentType);
        } else {
            $response = $this->generateGenericErrorResponse($response, $exception, $contentType);
        }

        return $response;
    }

    /**
     * Generate JSON error response from exception.
     *
     * @param ResponseInterface $response
     * @param \Exception $exception
     * @return ResponseInterface
     */
    public function generateJsonErrorResponse(
        ResponseInterface $response,
        \Exception $exception
    ): ResponseInterface {
        $contentType = 'application/json';

        if ($exception instanceof PronosticAppException) {
            $response = $this->generateSpecificErrorResponse($response, $exception, $contentType);
        } else {
            $response = $this->generateGenericErrorResponse($response, $exception, $contentType);
        }

        return $response;
    }

    /**
     * Generate error response.
     *
     * @param ResponseInterface $response
     * @param PronosticAppException $exception
     * @param string $contentType
     * @return ResponseInterface
     */
    protected function generateSpecificErrorResponse(
        ResponseInterface $response,
        PronosticAppException $exception,
        string $contentType
    ): ResponseInterface {
        // Prepare new response
        $newResponse = $response->withHeader(
            'Content-Type',
            $contentType
        );
        $newResponse = $newResponse->withStatus($exception->getResponseCode(), $exception->getResponseStatus());
        $newResponse->getBody()
            ->write($this->resourceGenerator->createMessageResource($exception->convertToMessageResult()));
        return $newResponse;
    }

    /**
     * Generate JSON error response.
     *
     * @param ResponseInterface $response
     * @param PronosticAppException $exception
     * @return ResponseInterface
     */
    protected function generateJsonSpecificErrorResponse(
        ResponseInterface $response,
        PronosticAppException $exception
    ): ResponseInterface {
        return $this->generateSpecificErrorResponse($response, $exception, 'application/json');
    }

    /**
     * Generate error response.
     *
     * @param ResponseInterface $response
     * @param \Exception $exception
     * @param string $contentType
     * @return ResponseInterface
     */
    protected function generateGenericErrorResponse(
        ResponseInterface $response,
        \Exception $exception,
        string $contentType
    ): ResponseInterface {
        $result = new MessageResult();
        $result->setDescription($exception->getMessage());

        // Prepare new response
        $newResponse = $response->withHeader(
            'Content-Type',
            $contentType
        );
        $newResponse = $newResponse->withStatus(400, 'Error durante la respuesta');
        $newResponse->getBody()
            ->write($this->resourceGenerator->createMessageResource($result));
        return $newResponse;
    }

    /**
     * Generate JSON error response.
     *
     * @param ResponseInterface $response
     * @param \Exception $exception
     * @return ResponseInterface
     */
    protected function generateJsonGenericErrorResponse(
        ResponseInterface $response,
        \Exception $exception
    ): ResponseInterface {
        return $this->generateGenericErrorResponse($response, $exception, 'application/json');
    }
}
