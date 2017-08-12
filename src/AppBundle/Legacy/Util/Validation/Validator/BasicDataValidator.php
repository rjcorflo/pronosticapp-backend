<?php

namespace AppBundle\Legacy\Util\Validation\Validator;

use Respect\Validation\Validator as V;
use AppBundle\Legacy\Util\General\ErrorCodes;
use Respect\Validation\Exceptions\NestedValidationException;

/**
 * Validator for basic data.
 */
class BasicDataValidator extends AbstractValidator
{
    /**
     * @param $identifier
     * @return $this
     */
    public function validateId($identifier)
    {
        try {
            V::intVal()->assert($identifier);
        } catch (NestedValidationException $e) {
            $this->result->isError();
            $this->result->addMessageWithCode(
                ErrorCodes::INVALID_ID,
                sprintf("Error validando el campo 'url': %s", $e->getFullMessage())
            );
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function validate(): void
    {
        if ($this->result->hasError()) {
            $this->result->setDescription('Error validando los datos');
        }

        parent::validate();
    }
}
