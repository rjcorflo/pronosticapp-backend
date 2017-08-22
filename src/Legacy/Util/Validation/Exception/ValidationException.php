<?php

namespace App\Legacy\Util\Validation\Exception;

use App\Legacy\Model\Exception\PronosticAppException;

/**
 * Exception when there is an error in data validation.
 *
 * @package RJ\PronosticApp\Util\Validation\Exception
 */
class ValidationException extends PronosticAppException
{
    protected $responseCode = 400;

    protected $responseStatus = 'Error en la validacion de los datos';

    protected $message = 'Error validando los datos';
}
