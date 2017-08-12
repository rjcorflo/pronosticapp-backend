<?php

namespace AppBundle\Legacy\Util\Validation;

use AppBundle\Legacy\Util\Validation\Validator\BasicDataValidator;
use AppBundle\Legacy\Util\Validation\Validator\CommunityValidator;
use AppBundle\Legacy\Util\Validation\Validator\ExistenceValidator;
use AppBundle\Legacy\Util\Validation\Validator\PlayerValidator;

/**
 * Group validators.
 *
 * @package RJ\PronosticApp\Util\Validation
 */
class GeneralValidator implements ValidatorInterface
{
    /**
     * @var PlayerValidator
     */
    private $playerValidator;

    /**
     * @var CommunityValidator
     */
    private $communityValidator;

    /**
     * @var ExistenceValidator
     */
    private $existenceValidator;

    /**
     * @var BasicDataValidator
     */
    private $basicDataValidator;

    /**
     * GeneralValidator constructor.
     * @param PlayerValidator $playerValidator
     * @param CommunityValidator $communityValidator
     * @param ExistenceValidator $existenceValidator
     * @param BasicDataValidator $basicDataValidator
     */
    public function __construct(
        PlayerValidator $playerValidator,
        CommunityValidator $communityValidator,
        ExistenceValidator $existenceValidator,
        BasicDataValidator $basicDataValidator
    ) {
        $this->playerValidator = $playerValidator;
        $this->communityValidator = $communityValidator;
        $this->existenceValidator = $existenceValidator;
        $this->basicDataValidator = $basicDataValidator;
    }

    /**
     * @inheritdoc
     */
    public function playerValidator() : PlayerValidator
    {
        return $this->playerValidator;
    }

    /**
     * @inheritdoc
     */
    public function communityValidator() : CommunityValidator
    {
        return $this->communityValidator;
    }

    /**
     * @inheritdoc
     */
    public function existenceValidator() : ExistenceValidator
    {
        return $this->existenceValidator;
    }

    /**
     * @inheritdoc
     */
    public function basicValidator() : BasicDataValidator
    {
        return $this->basicDataValidator;
    }
}
