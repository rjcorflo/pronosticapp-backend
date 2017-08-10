<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class LoadUsers extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function getOrder()
    {
        return 10;
    }

    public function load(ObjectManager $manager)
    {
        $encoder = $this->container->get('security.password_encoder');

        // 'John Smith' is the admin user allowed to access the EasyAdmin Demo
        $user = new User();
        $user->setUsername('john.smith');
        $user->setEmail('john.smith@example.com');
        $user->setRoles(array('ROLE_ADMIN'));
        $user->setEnabled(true);
        $user->setPassword($encoder->encodePassword($user, '1234'));
        $manager->persist($user);
        $manager->flush();
    }
}
