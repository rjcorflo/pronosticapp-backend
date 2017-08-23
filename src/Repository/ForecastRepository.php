<?php

namespace App\Repository;

use App\Entity\Community;
use App\Entity\Forecast;
use App\Entity\Matchday;
use App\Entity\Player;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;

class ForecastRepository extends EntityRepository
{
    /**
     * Find all forecasts from a certain player, community and matchday.
     *
     * @param Community $community
     * @param Player $player
     * @param Matchday $matchday
     * @return Forecast[]
     */
    public function findAllFromCommunity(
        Community $community,
        Player $player,
        Matchday $matchday
    ): array {
        $qb = $this->createQueryBuilder('f');
        $qb
            ->join('f.match', 'm', Expr\Join::WITH, 'm.matchday = :matchday')
            ->where($qb->expr()->eq('f.player', ':player'))
            ->andWhere($qb->expr()->eq('f.community', ':community'))
            ->setParameters([
                'matchday' => $matchday,
                'player' => $player,
                'community' => $community
            ]);

        $forecasts = $qb->getQuery()->getResult();

        return $forecasts;
    }

    /**
     * Find all forecast from a Community updated after a certain date.
     *
     * If date is NULL retrieve all.
     *
     * @param Community $community
     * @param \DateTime|null $date
     * @return array
     */
    public function findByCommunity(Community $community, \DateTime $date = null): array
    {
        if ($date !== null) {
            $queryBuilder = $this->createQueryBuilder('f');
            $queryBuilder
                ->where($queryBuilder->expr()->eq('f.community', ':community'))
                ->andWhere($queryBuilder->expr()->gt('f.updated', ':date'))
                ->setParameters(['community' => $community, 'date' => $date]);

            $matches = $queryBuilder->getQuery()->getResult();
        } else {
            $matches = $this->findBy(['community' => $community->getId()]);
        }

        return $matches;
    }
}
