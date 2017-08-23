<?php

namespace App\Legacy\Model\Exception;

use App\Legacy\Util\General\ErrorCodes;
use App\Legacy\Util\General\MessageResult;

/**
 * Base exception class for application.
 */
class PronosticAppException extends \Exception
{
    /**
     * Response code.
     *
     * @var int
     */
    protected $responseCode = 400;

    /**
     * Response status.
     *
     * @var string
     */
    protected $responseStatus = 'Error en la respuesta';

    /**
     * @var array
     */
    protected $messages = [];

    /**
     * Retrieve HTTP code for the response.
     *
     * @return int
     */
    public function getResponseCode(): int
    {
        return $this->responseCode;
    }

    /**
     * Retrieve response status.
     *
     * @return string
     */
    public function getResponseStatus(): string
    {
        return $this->responseStatus;
    }

    /**
     * @param int $code
     * @param null|string $observations
     */
    public function addMessageWithCode(int $code, ?string $observations) : void
    {
        $message['code'] = $code;
        $message['obs'] = $observations;

        $this->messages[] = $message;
    }

    /**
     * @param null|string $observations
     */
    public function addDefaultMessage(?string $observations) : void
    {
        $message['code'] = ErrorCodes::DEFAULT_ERROR;
        $message['obs'] = $observations;

        $this->messages[] = $message;
    }

    /**
     * @return array
     */
    public function getMessages() : array
    {
        return $this->messages;
    }

    /**
     * Fill exception data from MessageResult object.
     *
     * @param MessageResult $messageResult
     * @return static
     */
    public static function createFromMessageResult(MessageResult $messageResult)
    {
        $exception = new static($messageResult->getDescription());

        foreach ($messageResult->getMessages() as $messageItem) {
            $item['code'] = $messageItem->getCode();
            $item['obs'] = $messageItem->getObservation();
            $exception->messages[] = $item;
        }

        return $exception;
    }

    /**
     * Convert exception data to MessageResult object.
     *
     * @return MessageResult
     */
    public function convertToMessageResult(): MessageResult
    {
        $result = new MessageResult();
        $result->isError();
        $result->setDescription($this->getMessage());

        foreach ($this->messages as $message) {
            $result->addMessageWithCode($message['code'], $message['obs']);
        }

        return $result;
    }
}
