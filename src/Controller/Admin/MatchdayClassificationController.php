<?php

namespace App\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use Psr\Log\LoggerInterface;

class MatchdayClassificationController extends BaseAdminController
{
    /** @var LoggerInterface  */
    private $logger;

    /**
     * @inheritDoc
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }


    /**
     * This method overrides the default query builder used to search for this
     * entity. This allows to make a more complex search joining related entities.
     */
    protected function createSearchQueryBuilder($entityClass, $searchQuery, array $searchableFields, $sortField = null, $sortDirection = null, $dqlFilter = null)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManagerForClass($this->entity['class']);

        $queryBuilder = $em->createQueryBuilder();
        $queryBuilder
            ->select('entity')
            ->from($this->entity['class'], 'entity')
            ->join('entity.matchday', 'matchday')
            ->join('entity.player', 'player')
            ->join('entity.community', 'community')
            ->orWhere('LOWER(player.nickname) LIKE :query')
            ->orWhere('LOWER(matchday.name) LIKE :query')
            ->orWhere('LOWER(community.name) LIKE :query')
            ->setParameter('query', '%'.strtolower($searchQuery).'%');

        if (!empty($dqlFilter)) {
            $queryBuilder->andWhere($dqlFilter);
        }

        if (null !== $sortField) {
            $queryBuilder->orderBy('entity.'.$sortField, $sortDirection ?: 'DESC');
        }

        return $queryBuilder;
    }
}
