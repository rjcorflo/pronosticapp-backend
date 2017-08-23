<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Token model.
 *
 * @ORM\Entity
 * @ORM\Table(name="tokens")
 */
class Token
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Player
     *
     * @ORM\ManyToOne(targetEntity="Player", fetch="EAGER")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $player;

    /**
     * @var string
     *
     * @ORM\Column(name="token_string", type="string")
     */
    private $tokenString;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expire_at", type="datetime")
     */
    private $expireAt;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * @param Player $player
     * @return Token
     */
    public function setPlayer(Player $player): Token
    {
        $this->player = $player;
        return $this;
    }

    /**
     * @return string
     */
    public function getTokenString(): string
    {
        return $this->tokenString;
    }

    /**
     * @param string $tokenString
     * @return Token
     */
    public function setTokenString(string $tokenString): Token
    {
        $this->tokenString = $tokenString;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getExpireAt(): \DateTime
    {
        return $this->expireAt;
    }

    /**
     * @param \DateTime $expireAt
     * @return Token
     */
    public function setExpireAt(\DateTime $expireAt): Token
    {
        $this->expireAt = $expireAt;
        return $this;
    }

    /**
     * Generate a random string token.
     */
    public function generateRandomToken(): void
    {
        $token = bin2hex(random_bytes(20));
        $this->setTokenString($token);
    }
}
