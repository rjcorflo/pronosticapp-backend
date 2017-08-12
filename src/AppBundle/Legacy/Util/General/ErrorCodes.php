<?php

namespace AppBundle\Legacy\Util\General;

/**
 * List of Error Codes.
 */
class ErrorCodes
{
    const DEFAULT_ERROR = 0;

    const PLAYER_EMAIL_ALREADY_EXISTS = 1;

    const PLAYER_USERNAME_ALREADY_EXISTS = 2;

    const COMMUNITY_NAME_ALREADY_EXISTS = 3;

    const INVALID_ID = 4;

    const INVALID_PLAYER_USERNAME = 5;

    const INVALID_PLAYER_PASSWORD = 6;

    const INVALID_PLAYER_EMAIL = 7;

    const INVALID_PLAYER_FIRSTNAME= 8;

    const INVALID_PLAYER_LASTNAME = 9;

    const INVALID_PLAYER_COLOR = 10;

    const INVALID_COMMUNITY_NAME = 11;

    const INVALID_COMMUNITY_PASSWORD = 12;

    const INCORRECT_PASSWORD = 13;

    const INCORRECT_USERNAME = 14;

    const INVALID_PLAYER_IDAVATAR = 15;

    const MISSING_PARAMETERS = 16;

    const PLAYER_IS_NOT_MEMBER = 17;

    const CANNOT_RETRIEVE_RANDOM_COMMUNITY = 18;

    const ENTITY_NOT_FOUND = 19;

    const PLAYER_IS_ALREADY_MEMBER = 20;

    const COMMUNITY_IS_NOT_PRIVATE = 21;

    const INCORRECT_DATE = 22;
}
