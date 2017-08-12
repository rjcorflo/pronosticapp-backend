<?php

namespace AppBundle\Legacy\Model\Exception;

/**
 * Exception when entity not found in repository.
 *
 * @package RJ\PronosticApp\Model\Repository\Exception
 */
class NotFoundException extends PronosticAppException
{
    protected $responseCode = 404;

    protected $responseStatus = 'No se ha encontrado el recurso';

    protected $message = 'Recurso no encontrado';
}
