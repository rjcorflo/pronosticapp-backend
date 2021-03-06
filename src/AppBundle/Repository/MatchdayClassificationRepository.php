<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Community;
use AppBundle\Entity\Matchday;
use AppBundle\Entity\MatchdayClassification;
use Doctrine\ORM\EntityRepository;

class MatchdayClassificationRepository extends EntityRepository
{
    /**
     * Find classification for community.
     *
     * @param Community $community
     * @return MatchdayClassification[]
     */
    public function findByCommunity(Community $community): array
    {
        $classification = $this->findBy(['community' => $community]);
        return $classification;
    }

    /**
     * Find classifications for community only after next matchday (or actual).
     * If a date is passed, only modified records after that date are returned.
     *
     * @param Community $community
     * @param Matchday $nextMatchday
     * @param \DateTime|null $date
     * @return array
     */
    public function findByCommunityUntilNextMatchdayModifiedAfterDate(
        Community $community,
        Matchday $nextMatchday,
        \DateTime $date = null
    ): array {
        $queryBuilder = $this->createQueryBuilder('clas');
        $queryBuilder
            ->where($queryBuilder->expr()->eq('clas.community', ':community'))
            ->andWhere($queryBuilder->expr()->lte('clas.matchday', ':matchday'))
            ->setParameters(['community' => $community, 'matchday' => $nextMatchday]);

        if ($date !== null) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->gt('clas.updated', ':date'))
                ->setParameter('date', $date);
        }

        $classifications = $queryBuilder->getQuery()->getResult();

        return $classifications;
    }

    /**
     * Return classification for one community and one matchday.
     *
     * @param Matchday $matchday
     * @param Community $community
     * @return MatchdayClassification[]
     */
    public function findOrderedByMatchdayAndCommunity(
        Matchday $matchday,
        Community $community
    ): array {
        $classifications = $this->findBy(
            ['community' => $community, 'matchday' => $matchday],
            [
                'basicPoints' => 'DESC',
                'hitsTenPoints' => 'DESC',
                'hitsFivePoints' => 'DESC',
                'hitsThreePoints' => 'DESC',
                'hitsTwoPoints' => 'DESC',
                'hitsOnePoints' => 'DESC',
                'hitsNegativePoints' => 'ASC'
            ]
        );

        return $classifications;
    }

    public function retrieveGeneralDataForAllUSers(Community $community, Matchday $matchday)
    {
        $dql = $this->getEntityManager()->createQuery(
            'SELECT m as matchday, p AS player,
                    SUM(m.totalPoints) AS points,
                    SUM(m.hitsTenPoints) as hits10,
                    SUM(m.hitsFivePoints) as hits5,
                    SUM(m.hitsThreePoints) as hits3,
                    SUM(m.hitsTwoPoints) as hits2,
                    SUM(m.hitsOnePoints) as hits1,
                    SUM(m.hitsNegativePoints) as hitsNeg,
                    (SELECT COUNT(m1.id) 
                       FROM AppBundle:MatchdayClassification m1
                      WHERE m1.community = :community
                        AND m1.matchday <= :matchday
                        AND m1.player = p
                        AND m1.pointsForPosition = 3) as times_first,
                    (SELECT COUNT(m2.id) 
                       FROM AppBundle:MatchdayClassification m2
                      WHERE m2.community = :community
                        AND m2.matchday <= :matchday
                        AND m2.player = p
                        AND m2.pointsForPosition = 2) as times_second,
                    (SELECT COUNT(m3.id) 
                       FROM AppBundle:MatchdayClassification m3
                      WHERE m3.community = :community
                        AND m3.matchday <= :matchday
                        AND m3.player = p
                        AND m3.pointsForPosition = 1) as times_third
                FROM AppBundle:MatchdayClassification m
                JOIN m.player p
               WHERE m.community = :community
                 AND m.matchday <= :matchday
               GROUP BY p
            '
        )->setParameters(['community' => $community, 'matchday' => $matchday]);

        $results = $dql->getResult();

        return $results;
    }
}