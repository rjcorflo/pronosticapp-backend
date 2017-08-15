<?php

namespace AppBundle\Legacy\Process;

use AppBundle\Entity\Community;
use AppBundle\Entity\Forecast;
use AppBundle\Entity\GeneralClassification;
use AppBundle\Entity\Match;
use AppBundle\Entity\Matchday;
use AppBundle\Entity\MatchdayClassification;
use AppBundle\Entity\Participant;
use AppBundle\Entity\Player;
use AppBundle\Repository\CommunityRepository;
use AppBundle\Repository\ForecastRepository;
use AppBundle\Repository\GeneralClassificationRepository;
use AppBundle\Repository\MatchdayClassificationRepository;
use AppBundle\Repository\MatchdayRepository;
use AppBundle\Repository\MatchRepository;
use AppBundle\Repository\PlayerRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class ClassificationCalculationProcess.
 *
 * Calculate classifications for all communities.
 */
class ClassificationCalculationProcess
{
    /**
     * @var \DateTime
     */
    private $actualDate;

    /**
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * @var PlayerRepository
     */
    private $playerRepository;

    /**
     * @var CommunityRepository
     */
    private $communityRepository;

    /**
     * @var MatchdayRepository
     */
    private $matchdayRepository;

    /** @var MatchRepository */
    private $matchRepository;

    /**
     * @var ForecastRepository
     */
    private $forecastRepository;

    /**
     * @var MatchdayClassificationRepository
     */
    private $matchdayClassRepo;

    /**
     * @var GeneralClassificationRepository
     */
    private $generalClassRepo;

    /**
     * @var int
     */
    private $position;

    /**
     * @var int
     */
    private $positionPoints;

    /**
     * @inheritDoc
     */
    public function __construct(ObjectManager $em)
    {
        $this->actualDate = new \DateTime();
        $this->entityManager = $em;
        $this->playerRepository = $this->entityManager->getRepository(Player::class);
        $this->communityRepository = $this->entityManager->getRepository(Community::class);
        $this->matchdayRepository = $this->entityManager->getRepository(Matchday::class);
        $this->matchRepository = $this->entityManager->getRepository(Match::class);
        $this->forecastRepository = $this->entityManager->getRepository(Forecast::class);
        $this->matchdayClassRepo = $this->entityManager
            ->getRepository(MatchdayClassification::class);
        $this->generalClassRepo = $this->entityManager
            ->getRepository(GeneralClassification::class);
    }

    /**
     * Launch process.
     *
     * Calculate classification for all communities.
     *
     */
    public function launch(): void
    {
        $matchdays = $this->matchdayRepository->findAllUntilNextMatchday();
        $communities = $this->communityRepository->findAll();

        foreach ($communities as $community) {
            foreach ($matchdays as $matchday) {
                $classificationUpdated = $this->calculateMatchdayClassificationForCommunity($matchday, $community);

                if ($classificationUpdated) {
                    $this->calculateGeneralClassificationForCommunity($matchday, $community);
                }
            }
        }
    }

    /**
     * @param Matchday $matchday
     * @param Community $community
     * @return bool
     */
    private function calculateMatchdayClassificationForCommunity(
        Matchday $matchday,
        Community $community
    ): bool {
        $classificationUpdated = false;

        $players = $community->getParticipants()->map( function (Participant $participant) { return $participant->getPlayer(); } )->toArray();

        foreach ($players as $player) {
            $classification = $this->matchdayClassRepo->findOneBy([
                    'player' => $player,
                    'community' => $community,
                    'matchday' => $matchday
                ]
            );

            // If there is already a record and there are no matches updated after record modified date
            // then don't update classification record
            if ($classification->getId() > 0) {
                $existMatchesModified = $this->matchRepository
                        ->countModifiedMatchesAfterDate($matchday, $classification->getLastModifiedDate()) > 0;

                if (!$existMatchesModified) {
                    continue;
                }
            }

            $forecasts = $this->forecastRepository->findAllFromCommunity($community, $player, $matchday);

            $points = 0;
            $hitsTen = 0;
            $hitsFive = 0;
            $hitsThree = 0;
            $hitsTwo = 0;
            $hitsOne = 0;
            $hitsNegative = 0;

            $beans = [];
            foreach ($forecasts as $forecast) {
                $forecast->calculateActualPoints();
                $forecast->setLastModifiedDate($this->actualDate);

                $points += $forecast->getPoints();
                $hitsTen += $forecast->getPoints() == 10 ? 1 : 0;
                $hitsFive += $forecast->getPoints() == 5 ? 1 : 0;
                $hitsThree += $forecast->getPoints() == 3 ? 1 : 0;
                $hitsTwo += $forecast->getPoints() == 2 ? 1 : 0;
                $hitsOne += $forecast->getPoints() == 1 ? 1 : 0;
                $hitsNegative += $forecast->getPoints() == -1 ? 1 : 0;

                $beans[] = $forecast;
            }

            $this->forecastRepository->storeMultiple($beans);

            $classification->setCommunity($community);
            $classification->setPlayer($player);
            $classification->setMatchday($matchday);
            $classification->setBasicPoints($points);
            $classification->setTotalPoints(0);
            $classification->setPointsForPosition(0);
            $classification->setHitsTenPoints($hitsTen);
            $classification->setHitsFivePoints($hitsFive);
            $classification->setHitsThreePoints($hitsThree);
            $classification->setHitsTwoPoints($hitsTwo);
            $classification->setHitsOnePoints($hitsOne);
            $classification->setHitsNegativePoints($hitsNegative);
            $classification->setLastModifiedDate($this->actualDate);

            $this->matchdayClassRepo->store($classification);

            // Classification has been updated
            $classificationUpdated = true;
        }

        if ($classificationUpdated) {
            $this->updateClassification($matchday, $community);
        }

        return $classificationUpdated;
    }

    /**
     * @param MatchdayInterface $matchday
     * @param CommunityInterface $community
     */
    private function updateClassification(
        MatchdayInterface $matchday,
        CommunityInterface $community
    ) {
        $classifications = $this->matchdayClassRepo->findOrderedByMatchdayAndCommunity($matchday, $community);

        $factor = $matchday->getPhase()->getMultiplierFactor();

        // Reset properties
        $this->position = 1;
        $this->positionPoints = 3;

        foreach ($classifications as $index => $classification) {
            if ($index == 0) {
                $classification->setPointsForPosition($this->positionPoints);

                $totalPoints = ($classification->getBasicPoints() * $factor) + $classification->getPointsForPosition();
                $classification->setTotalPoints($totalPoints);

                $classification->setPosition($this->position++);

                continue;
            }

            // Get previous classification
            $previousClassification = $classifications[$index - 1];

            // If has fewer points than previous, set point minus one
            if ($classification->getBasicPoints() != $previousClassification->getBasicPoints()) {
                $this->updateClassificationPosition($index, $classification, $factor);
                continue;
            }

            if ($classification->getHitsTenPoints() != $previousClassification->getHitsTenPoints()) {
                $this->updateClassificationPosition($index, $classification, $factor);
                continue;
            }

            if ($classification->getHitsFivePoints() != $previousClassification->getHitsFivePoints()) {
                $this->updateClassificationPosition($index, $classification, $factor);
                continue;
            }

            if ($classification->getHitsThreePoints() != $previousClassification->getHitsThreePoints()) {
                $this->updateClassificationPosition($index, $classification, $factor);
                continue;
            }

            if ($classification->getHitsTwoPoints() != $previousClassification->getHitsTwoPoints()) {
                $this->updateClassificationPosition($index, $classification, $factor);
                continue;
            }

            if ($classification->getHitsOnePoints() != $previousClassification->getHitsOnePoints()) {
                $this->updateClassificationPosition($index, $classification, $factor);
                continue;
            }

            $classification->setPointsForPosition($this->positionPoints);

            $totalPoints = ($classification->getBasicPoints() * $factor) + $classification->getPointsForPosition();
            $classification->setTotalPoints($totalPoints);

            $classification->setPosition($this->position++);
        }

        $this->matchdayClassRepo->storeMultiple($classifications);
    }

    /**
     * @param int $index
     * @param MatchdayclassificationInterface $classification
     * @param int $factor
     */
    private function updateClassificationPosition(
        int $index,
        MatchdayclassificationInterface $classification,
        int $factor
    ) {
        $classification->setPointsForPosition($this->positionPoints);

        $totalPoints = ($classification->getBasicPoints() * $factor) + $classification->getPointsForPosition();
        $classification->setTotalPoints($totalPoints);

        $classification->setPosition($this->position++);

        if ($this->positionPoints > 0 && $index < 3) {
            $this->positionPoints--;
        } else {
            $this->positionPoints = 0;
        }
    }

    /**
     * @param MatchdayInterface $matchday
     * @param CommunityInterface $community
     */
    private function calculateGeneralClassificationForCommunity(
        MatchdayInterface $matchday,
        CommunityInterface $community
    ) {
        // Must update classification for passed matchday until next matchday ...
        $nextMatchday = $this->matchdayRepository->getNextMatchday();

        $matchdaysToUpdate = $this->matchdayRepository->findAllBetweenMatchdays($matchday, $nextMatchday);

        foreach ($matchdaysToUpdate as $matchdayToUpdate) {
            $results = R::getAll(
                'SELECT player_id,
                        SUM(total_points) as points,
                        SUM(hits_ten_points) as hits10,
                        SUM(hits_five_points) as hits5,
                        SUM(hits_three_points) as hits3,
                        SUM(hits_two_points) as hits2,
                        SUM(hits_one_points) as hits1,
                        SUM(hits_negative_points) as hitsNeg,
                        (SELECT COUNT(1) 
                           FROM matchdayclassification m1
                          WHERE m1.player_id = m.player_id
                            AND m1.community_id = :community_id
                            AND m1.matchday_id <= :matchday_id
                            AND m1.position_points = 3) as times_first,
                        (SELECT COUNT(1) 
                           FROM matchdayclassification m1
                          WHERE m1.player_id = m.player_id
                            AND m1.community_id = :community_id
                            AND m1.matchday_id <= :matchday_id
                            AND m1.position_points = 2) as times_second,
                        (SELECT COUNT(1) 
                           FROM matchdayclassification m1
                          WHERE m1.player_id = m.player_id
                            AND m1.community_id = :community_id
                            AND m1.matchday_id <= :matchday_id
                            AND m1.position_points = 1) as times_third
                   FROM matchdayclassification m
                  WHERE m.community_id = :community_id
                    AND m.matchday_id <= :matchday_id
                  GROUP BY player_id
                ',
                [':community_id' => $community->getId(), ':matchday_id' => $matchday->getId()]
            );

            foreach ($results as $result) {
                $player = $this->playerRepository->getById($result['player_id']);

                $classification = $this->generalClassRepo->findOneOrCreate($player, $community, $matchday);
                $classification->setPlayer($player);
                $classification->setMatchday($matchday);
                $classification->setCommunity($community);
                $classification->setTotalPoints($result['points']);
                $classification->setHitsTenPoints($result['hits10']);
                $classification->setHitsFivePoints($result['hits5']);
                $classification->setHitsThreePoints($result['hits3']);
                $classification->setHitsTwoPoints($result['hits2']);
                $classification->setHitsOnePoints($result['hits1']);
                $classification->setHitsNegativePoints($result['hitsNeg']);
                $classification->setTimesFirst($result['times_first']);
                $classification->setTimesSecond($result['times_second']);
                $classification->setTimesThird($result['times_third']);
                $classification->setLastModifiedDate($this->actualDate);

                $this->generalClassRepo->store($classification);
            }

            $this->updateGeneralClassification($matchdayToUpdate, $community);
        }
    }

    /**
     * @param MatchdayInterface $matchday
     * @param CommunityInterface $community
     */
    private function updateGeneralClassification(
        MatchdayInterface $matchday,
        CommunityInterface $community
    ) {
        $classifications = $this->generalClassRepo->findOrderedByMatchdayAndCommunity($matchday, $community);

        // Reset properties
        $this->position = 1;

        foreach ($classifications as $index => $classification) {
            $classification->setPosition($this->position++);
        }

        $this->matchdayClassRepo->storeMultiple($classifications);
    }
}
