<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Player;
use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PlayerController extends BaseAdminController
{
    /** @var UserPasswordEncoderInterface */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @inheritDoc
     */
    protected function prePersistEntity($entity)
    {
        if ($entity instanceof Player) {
            $this->encodePlayerPassword($entity);
        }
    }

    /**
     * @inheritDoc
     */
    protected function preUpdateEntity($entity)
    {
        if ($entity instanceof Player) {
            $this->encodePlayerPassword($entity);
        }
    }

    private function encodePlayerPassword(Player $player)
    {
        $hashedPassword = $this->encoder->encodePassword($player, $player->getPassword());
        $player->setPassword($hashedPassword);
    }
}