<?php

namespace AppBundle\Legacy\Util\Validation\Validator;

use AppBundle\Legacy\Util\Validation\Exception\ValidationException;
use AppBundle\Legacy\Util\Validation\General\ValidationResult;

/**
 * Base class for validators.
 *
 * @package RJ\PronosticApp\Util\Validation\Validator
 */
abstract class AbstractValidator
{
    /**
     * @var ValidationResult
     */
    protected $result;

    /**
     * AbstractValidator constructor.
     */
    public function __construct()
    {
        $this->result = new ValidationResult();
    }

    /**
     * Validate data. Throws exception if there are errors.
     *
     * @throws ValidationException
     */
    public function validate(): void
    {
        if ($this->result->hasError()) {
            $exception = ValidationException::createFromMessageResult($this->result);
            throw $exception;
        }
    }
}
