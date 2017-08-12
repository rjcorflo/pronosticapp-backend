<?php

namespace AppBundle\Legacy\Model\Exception\Request;

use AppBundle\Legacy\Model\Exception\PronosticAppException;

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
