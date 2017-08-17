<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Community;
use AppBundle\Entity\Matchday;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityRepository;

class MatchRepository extends EntityRepository
{
    /**
     * @inheritDoc
     */
    public function findActivesByMatchday(int $idMatchday): array
    {
        $actualDate = new \DateTime();

        $queryBuilder = $this->createQueryBuilder('m');
        $queryBuilder
            ->where($queryBuilder->expr()->eq('m.matchday', ':matchday_id'))
            ->andWhere($queryBuilder->expr()->gt('m.startTime', ':date'))
            ->setParameters(['matchday_id' => $idMatchday, 'date' => $actualDate]);

        $matches = $queryBuilder->getQuery()->getResult();

        return $matches;
    }

    /**
     * @inheritDoc
     */
    public function findByCommunity(Community $community, \DateTime $date = null): array
    {
        $queryBuilder = $this->createQueryBuilder('m');

        if ($date !== null) {
            $queryBuilder
                ->where($queryBuilder->expr()->gt('m.updated', ':date'))
                ->setParameter('date', $date, Type::DATETIME);
        }

        $matches = $queryBuilder->getQuery()->getResult();

        return $matches;
    }

    /**
     * @inheritDoc
     */
    public function countModifiedMatchesAfterDate(Matchday $matchday, \DateTime $date): int
    {
        $queryBuilder = $this->createQueryBuilder('m');
        $queryBuilder
            ->select('COUNT(m.id)')
            ->where($queryBuilder->expr()->eq('m.matchday', ':matchday'))
            ->andWhere($queryBuilder->expr()->gt('m.startTime', ':date'))
            ->setParameter('matchday', $matchday)
            ->setParameter('date', $date, Type::DATETIME);

        $count = $queryBuilder->getQuery()->getSingleScalarResult();

        return $count;
    }
}
