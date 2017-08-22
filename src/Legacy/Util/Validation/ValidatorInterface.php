<?php

namespace App\Legacy\Util\Validation;

use App\Legacy\Util\Validation\Validator\BasicDataValidator;
use App\Legacy\Util\Validation\Validator\CommunityValidator;
use App\Legacy\Util\Validation\Validator\ExistenceValidator;
use App\Legacy\Util\Validation\Validator\PlayerValidator;

/**
 * Interface ValidatorInterface.
 *
 * Group validators.
 *
 * @package RJ\PronosticApp\Util\Validation
 */
interface ValidatorInterface
{
    /**
     * @return PlayerValidator
     */
    public function playerValidator() : PlayerValidator;

    /**
     * @return CommunityValidator
     */
    public function communityValidator() : CommunityValidator;

    /**
     * @return ExistenceValidator
     */
    public function existenceValidator() : ExistenceValidator;

    /**
     * @return BasicDataValidator
     */
    public function basicValidator() : BasicDataValidator;
}
