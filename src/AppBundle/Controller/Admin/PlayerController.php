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
            $hashedPassword = $this->encoder->encodePassword($entity, $entity->getPassword());
            $entity->setPassword($hashedPassword);
        }
    }

    /**
     * @inheritDoc
     */
    protected function preUpdateEntity($entity)
    {
        if ($entity instanceof Player && $entity->getPlainPassword() !== null) {
            $hashedPassword = $this->encoder->encodePassword($entity, $entity->getPlainPassword());
            $entity->setPassword($hashedPassword);
        }
    }
}
