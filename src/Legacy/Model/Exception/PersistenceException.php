<?php

namespace App\Legacy\Model\Exception;

/**
 * Exception when entity can not be saved in repository.
 *
 * @package RJ\PronosticApp\Model\Repository\Exception
 */
class PersistenceException extends PronosticAppException
{
    protected $responseCode = 404;

    protected $responseStatus = 'Error persistiendo los datos';

    protected $message = 'Error durante la persistencia de datos';
}
