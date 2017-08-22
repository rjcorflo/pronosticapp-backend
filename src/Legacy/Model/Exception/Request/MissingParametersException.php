<?php

namespace App\Legacy\Model\Exception\Request;

use App\Legacy\Model\Exception\PronosticAppException;

/**
 * Exception when there are missing parameters in the request.
 *
 * @package RJ\PronosticApp\Model\Repository\Exception
 */
class MissingParametersException extends PronosticAppException
{
    protected $responseCode = 400;

    protected $responseStatus = 'Faltan parametros';

    protected $message = 'Faltan parametros';
}
