<?php

namespace App\Legacy\Util\Validation\Validator;

use App\Entity\Player;
use App\Legacy\Util\General\ErrorCodes;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as V;

/**
 * Validate player data.
 *
 * @package RJ\PronosticApp\Util\Validation\Validator
 */
class PlayerValidator extends AbstractValidator
{
    /**
     * Validate attributes from player.
     *
     * @param Player $player
     * @return $this
     */
    public function validatePlayerData(Player $player)
    {
        try {
            V::alnum()->length(4, 20)->assert($player->getNickname());
        } catch (NestedValidationException $e) {
            $this->result->isError();
            $this->result->addMessageWithCode(
                ErrorCodes::INVALID_PLAYER_USERNAME,
                $e->getFullMessage()
            );
        }

        try {
            V::notBlank()->notOptional()->notEmpty()->assert($player->getPassword());
        } catch (NestedValidationException $e) {
            $this->result->isError();
            $this->result->addMessageWithCode(
                ErrorCodes::INVALID_PLAYER_PASSWORD,
                $e->getFullMessage()
            );
        }

        try {
            V::email()->assert($player->getEmail());
        } catch (NestedValidationException $e) {
            $this->result->isError();
            $this->result->addMessageWithCode(
                ErrorCodes::INVALID_PLAYER_EMAIL,
                $e->getFullMessage()
            );
        }

        try {
            V::optional(V::alpha()->length(3, 60))->assert($player->getFirstName());
        } catch (NestedValidationException $e) {
            $this->result->isError();
            $this->result->addMessageWithCode(
                ErrorCodes::INVALID_PLAYER_FIRSTNAME,
                $e->getFullMessage()
            );
        }

        try {
            V::optional(V::alpha()->length(3, 100))->assert($player->getLastName());
        } catch (NestedValidationException $e) {
            $this->result->isError();
            $this->result->addMessageWithCode(
                ErrorCodes::INVALID_PLAYER_LASTNAME,
                $e->getFullMessage()
            );
        }

        try {
            V::intVal()->assert($player->getAvatar());
        } catch (NestedValidationException $e) {
            $this->result->isError();
            $this->result->addMessageWithCode(
                ErrorCodes::INVALID_PLAYER_IDAVATAR,
                sprintf("Error validando el campo 'id_avatar': %s", $e->getFullMessage())
            );
        }

        try {
            V::hexRgbColor()->assert($player->getColor());
        } catch (NestedValidationException $e) {
            $this->result->isError();
            $this->result->addMessageWithCode(
                ErrorCodes::INVALID_PLAYER_COLOR,
                sprintf("Error validando el campo 'color': %s", $e->getFullMessage())
            );
        }
        
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function validate(): void
    {
        if ($this->result->hasError()) {
            $this->result->setDescription('Error validando los datos del jugador');
        }

        parent::validate();
    }
}
