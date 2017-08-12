<?php

namespace AppBundle\Legacy\Util\Validation;

use AppBundle\Legacy\Util\Validation\Validator\BasicDataValidator;
use AppBundle\Legacy\Util\Validation\Validator\CommunityValidator;
use AppBundle\Legacy\Util\Validation\Validator\ExistenceValidator;
use AppBundle\Legacy\Util\Validation\Validator\PlayerValidator;

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
