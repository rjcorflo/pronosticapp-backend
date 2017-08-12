<?php

namespace AppBundle\Legacy\Util\General;

/**
 * Message result for control and response.
 *
 * @package RJ\PronosticApp\Util\General
 */
class MessageResult
{
    /**
     * @var bool
     */
    protected $error = false;

    /**
     * @var string
     */
    protected $description = "";

    /**
     * @var MessageItem[]
     */
    protected $messages = [];

    /**
     * @param bool $error
     */
    public function setError(bool $error)
    {
        $this->error = $error;
    }

    /**
     * Mark message as error.
     */
    public function isError() : void
    {
        $this->error = true;
    }

    /**
     * @return bool
     */
    public function hasError() : bool
    {
        return $this->error;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description) : void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription() : string
    {
        return $this->description;
    }

    /**
     * @param MessageItem $message
     */
    public function addMessage(MessageItem $message) : void
    {
        $this->messages[] = $message;
    }

    /**
     * @param int $code
     * @param null|string $observations
     */
    public function addMessageWithCode(int $code, ?string $observations) : void
    {
        $message = new MessageItem();
        $message->setCode($code);
        $message->setObservation($observations);

        $this->messages[] = $message;
    }

    /**
     * Add message with code equals to {@link ErrorCodes::DEFAULT_ERROR}.
     *
     * @param null|string $observations
     */
    public function addDefaultMessage(?string $observations) : void
    {
        $message = new MessageItem();
        $message->setCode(ErrorCodes::DEFAULT_ERROR);
        $message->setObservation($observations);

        $this->messages[] = $message;
    }

    /**
     * @return MessageItem[]
     */
    public function getMessages() : array
    {
        return $this->messages;
    }
}
