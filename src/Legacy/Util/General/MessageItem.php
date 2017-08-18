<?php

namespace App\Legacy\Util\General;

/**
 * Class MessageItem.
 *
 * @package RJ\PronosticApp\Util\General
 */
class MessageItem
{
    /** @var  int $code */
    private $code;

    /** @var  string $observation */
    private $observation;

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @param int $code
     */
    public function setCode(int $code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getObservation(): string
    {
        return $this->observation;
    }

    /**
     * @param string $observation
     */
    public function setObservation(string $observation)
    {
        $this->observation = $observation;
    }
}
