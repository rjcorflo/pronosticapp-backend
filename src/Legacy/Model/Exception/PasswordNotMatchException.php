<?php

namespace App\Legacy\Model\Exception;

/**
 * Exception when passwords not match.
 *
 * @package RJ\PronosticApp\Model\Repository\Exception
 */
class PasswordNotMatchException extends PronosticAppException
{
    protected $responseCode = 401;

    protected $responseStatus = 'Password incorrecta';

    protected $message = 'Passwords no coinciden';
}
