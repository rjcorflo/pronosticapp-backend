<?php

namespace AppBundle\Legacy\Util\Validation\Validator;

use AppBundle\Entity\Community;
use AppBundle\Entity\Participant;
use AppBundle\Entity\Player;
use AppBundle\Legacy\Util\General\ErrorCodes;
use AppBundle\Repository\CommunityRepository;
use AppBundle\Repository\ParticipantRepository;
use AppBundle\Repository\PlayerRepository;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Validate data existence.
 */
class ExistenceValidator extends AbstractValidator
{
    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * ExistenceValidator constructor.
     * @param ObjectManager $manager
     */
    public function __construct(
        ObjectManager $manager
    ) {
        parent::__construct();
        $this->manager = $manager;
    }

    /**
     * Check if nickname of player is already in use.
     *
     * @param Player $player
     * @return $this
     */
    public function checkIfNicknameExists(Player $player)
    {
        /** @var PlayerRepository $playerRepository */
        $playerRepository = $this->manager->getRepository(Player::class);

        try {
            $existsNickname = $playerRepository->checkNickameExists($player->getNickname());

            if ($existsNickname) {
                $this->result->isError();
                $this->result->addMessageWithCode(
                    ErrorCodes::PLAYER_USERNAME_ALREADY_EXISTS,
                    "Ya existe un usuario con ese nickname."
                );
            }
        } catch (\Throwable $e) {
            $this->result->isError();
            $this->result->addMessageWithCode(
                ErrorCodes::DEFAULT_ERROR,
                "Error comprobando la existencia del nickname."
            );
        }

        return $this;
    }

    /**
     * Check if email is already in use.
     *
     * @param Player $player
     * @return $this
     */
    public function checkIfEmailExists(Player $player)
    {
        /** @var PlayerRepository $playerRepository */
        $playerRepository = $this->manager->getRepository(Player::class);

        try {
            $existsEmail = $playerRepository->checkEmailExists($player->getEmail());

            if ($existsEmail) {
                $this->result->isError();
                $this->result->addMessageWithCode(
                    ErrorCodes::PLAYER_EMAIL_ALREADY_EXISTS,
                    "Ya existe un usuario con ese email."
                );
            }
        } catch (\Throwable $e) {
            $this->result->isError();
            $this->result->addMessageWithCode(
                ErrorCodes::DEFAULT_ERROR,
                "Error comprobando la existencia del email."
            );
        }

        return $this;
    }

    /**
     * Check if community name is already in use.
     *
     * @param Community $community
     * @return $this
     */
    public function checkIfNameExists(Community $community)
    {
        /** @var CommunityRepository $communityRepository */
        $communityRepository = $this->manager->getRepository(Community::class);

        try {
            $existsName = $communityRepository->checkIfNameExists($community->getName());

            if ($existsName) {
                $this->result->isError();
                $this->result->addMessageWithCode(
                    ErrorCodes::COMMUNITY_NAME_ALREADY_EXISTS,
                    "Ya existe una comunidad con ese nombre."
                );
            }
        } catch (\Throwable $e) {
            $this->result->isError();
            $this->result->addMessageWithCode(
                ErrorCodes::DEFAULT_ERROR,
                "Error comprobando la existencia del nombre de la comunidad."
            );
        }

        return $this;
    }

    /**
     * Check if player is already a member from community.
     *
     * @param Player $player
     * @param Community $community
     * @return $this
     */
    public function checkIfPlayerIsAlreadyFromCommunity(Player $player, Community $community)
    {
        /** @var ParticipantRepository $participantRepo */
        $participantRepo = $this->manager->getRepository(Participant::class);

        try {
            $exists = $participantRepo->checkIfPlayerIsAlreadyFromCommunity($player, $community);

            if ($exists) {
                $this->result->isError();
                $this->result->addMessageWithCode(
                    ErrorCodes::PLAYER_IS_ALREADY_MEMBER,
                    sprintf(
                        'El jugador %s ya es miembro de la comunidad %s.',
                        $player->getNickname(),
                        $community->getName()
                    )
                );
            }
        } catch (\Throwable $e) {
            $this->result->isError();
            $this->result->addMessageWithCode(
                ErrorCodes::DEFAULT_ERROR,
                "Error comprobando si el jugador ya participa en la comunidad pasada."
            );
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function validate(): void
    {
        if ($this->result->hasError()) {
            $this->result->setDescription("Error registro existente");
        }

        parent::validate();
    }
}
