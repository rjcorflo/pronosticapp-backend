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
}