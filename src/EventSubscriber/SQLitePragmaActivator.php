<?php

namespace App\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Symfony\Bridge\Doctrine\RegistryInterface;

class SQLitePragmaActivator implements EventSubscriber
{
    private $doctrine;

    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getSubscribedEvents()
    {
        return [
            'preFlush'
        ];
    }

    public function preFlush(PreFlushEventArgs $args)
    {
        $this->doctrine->getConnection()->exec('PRAGMA foreign_keys = ON');
    }
}