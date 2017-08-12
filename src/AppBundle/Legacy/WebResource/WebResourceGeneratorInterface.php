<?php

namespace AppBundle\Legacy\WebResource;

use AppBundle\Entity\Community;
use AppBundle\Entity\Forecast;
use AppBundle\Entity\Image;
use AppBundle\Entity\Match;
use AppBundle\Entity\Matchday;
use AppBundle\Entity\MatchdayClassification;
use AppBundle\Entity\Player;
use AppBundle\Entity\Token;
use AppBundle\Legacy\Util\General\ForecastResult;
use AppBundle\Legacy\Util\General\MessageResult;

/**
 * Interface WebResourceGeneratorInterface.
 *
 * Creates web resource from entities.
 *
 * @package RJ\PronosticApp\WebResource
 */
interface WebResourceGeneratorInterface
{
    /**
     * Return type JSON.
     * @var string
     */
    const JSON = 'json';

    /**
     * Return type array
     * @var string
     */
    const ARRAY = 'array';

    /**
     * @param string $includes
     * @return $this
     */
    public function include(string $includes);

    /**
     * Exclude internal Resource
     *
     * @param string $excludes
     * @return $this
     */
    public function exclude(string $excludes);

    /**
     * @param MessageResult $messages
     * @param string $resultType
     * @return array|string
     */
    public function createMessageResource(MessageResult $messages, $resultType = self::JSON);

    /**
     * @param ForecastResult $messages
     * @param string $resultType
     * @return array|string
     */
    public function createForecastMessageResource(ForecastResult $messages, $resultType = self::JSON);

    /**
     * @param Player | Player[] $players
     * @param string $resultType
     * @return array|string
     */
    public function createPlayerResource($players, $resultType = self::JSON);

    /**
     * @param Community | Community[] $communities
     * @param string $resultType
     * @return array|string
     */
    public function createCommunityResource($communities, $resultType = self::JSON);

    /**
     * @param  Player $player
     * @param  Community[] $communities
     * @param  string $resultType
     * @return array|string
     */
    public function createCommunityListResource($player, $communities, $resultType = self::JSON);

    /**
     * @param Community $community
     * @param Player[] $players
     * @param Matchday[] $matchdays
     * @param Match[] $matches
     * @param Forecast[] $forecasts
     * @param MatchdayClassification[] $classifications
     * @param string $resultType
     * @return mixed
     */
    public function createCommunityDataResource(
        $community,
        $players,
        $matchdays,
        $matches,
        $forecasts,
        $classifications,
        $resultType = self::JSON
    );

    /**
     * @param Community$community
     * @param Matchday[] $matchdays
     * @return mixed
     */
    public function createGeneralClassificationCommunityResource(
        $community,
        $matchdays,
        $resultType = self::JSON
    );

    /**
     * @param Token|Token[] $tokens
     * @param string $resultType
     * @return array|string
     */
    public function createTokenResource($tokens, $resultType = self::JSON);

    /**
     * @param Image|Image[] $images
     * @param string $resultType
     * @return array|string
     */
    public function createImageResource($images, $resultType = self::JSON);

    /**
     * @param Match[] $matches
     * @param string $resultType
     * @return mixed
     */
    public function createActiveMatchesResource($matches, $resultType = self::JSON);
}
