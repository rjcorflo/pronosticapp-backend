<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Community;
use AppBundle\Entity\Matchday;
use AppBundle\Entity\Player;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;

class ForecastRepository extends EntityRepository
{
    /**
     * @inheritDoc
     */
    public function findAllFromCommunity(
        Community $community,
        Player $player,
        Matchday $matchday
    ): array {
        $qb = $this->createQueryBuilder('f');
        $qb
            ->join('f.match', 'm', Expr\Join::WITH, 'm.matchday = :matchday_id')
            ->where($qb->expr()->eq('f.player', ':player_id'))
            ->andWhere($qb->expr()->eq('f.community', ':community_id'))
            ->setParameters([
                'matchday_id' => $matchday->getId(),
                'player_id' => $player->getId(),
                'community_id' => $community->getId()
            ]);

        $forecasts = $qb->getQuery()->getResult();

        return $forecasts;
    }

    /**
     * @inheritDoc
     */
    public function findByCommunity(Community $community, \DateTime $date = null): array
    {
        if ($date !== null) {
            $queryBuilder = $this->createQueryBuilder('f');
            $queryBuilder
                ->where($queryBuilder->expr()->eq('f.community', ':community_id'))
                ->andWhere($queryBuilder->expr()->gt('f.updated', ':date'))
                ->setParameters(['community_id' => $community->getId(), 'date' => $date]);

            $matches = $queryBuilder->getQuery()->getResult();
        } else {
            $matches = $this->findBy(['community' => $community->getId()]);
        }

        return $matches;
    }
}
