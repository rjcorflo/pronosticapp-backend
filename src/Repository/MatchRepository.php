<?php

namespace App\Repository;

use App\Entity\Community;
use App\Entity\Match;
use App\Entity\Matchday;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityRepository;

class MatchRepository extends EntityRepository
{
    /**
     * Return list of active matches for a certain matchday.
     *
     * Active matches are those whose startTime is greater than passed date (or actual date).
     *
     * @param int $idMatchday
     * @param \DateTime|null $date      If date is NULL actual date is used.
     * @return Match[]                  List of matches.
     */
    public function findActivesByMatchday(int $idMatchday, \DateTime $date = null): array
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
     * Find updated matches for a certain Community.
     *
     * TODO Now return all matches.
     *
     * @param Community $community
     * @param \DateTime|null $date
     * @return array
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
     * Return TRUE if there are matches updated after date for a certain matchday.
     *
     * @param Matchday $matchday
     * @param \DateTime $date
     * @return bool
     */
    public function existsMatchesModifiedAfterDate(Matchday $matchday, \DateTime $date): bool
    {
        $queryBuilder = $this->createQueryBuilder('m');
        $queryBuilder
            ->select('COUNT(m.id)')
            ->where($queryBuilder->expr()->eq('m.matchday', ':matchday'))
            ->andWhere($queryBuilder->expr()->gt('m.updated', ':date'))
            ->setParameter('matchday', $matchday)
            ->setParameter('date', $date, Type::DATETIME);

        $count = $queryBuilder->getQuery()->getSingleScalarResult();

        return $count > 0;
    }
}
