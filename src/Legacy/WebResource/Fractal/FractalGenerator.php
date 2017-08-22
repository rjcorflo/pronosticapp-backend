<?php

namespace App\Legacy\WebResource\Fractal;

use App\App;
use App\Entity\Community;
use App\Entity\Image;
use App\Entity\Player;
use App\Entity\Token;
use App\Legacy\Util\General\ForecastResult;
use App\Legacy\Util\General\MessageResult;
use App\Legacy\WebResource\Fractal\Resource\CommunityDataResource;
use App\Legacy\WebResource\Fractal\Resource\CommunityListResource;
use App\Legacy\WebResource\Fractal\Resource\GeneralClassificationResource;
use App\Legacy\WebResource\Fractal\Resource\MatchListResource;
use App\Legacy\WebResource\Fractal\Serializer\NoDataArraySerializer;
use App\Legacy\WebResource\WebResourceGeneratorInterface;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Scope;
use App\Legacy\WebResource\Fractal\Transformer\CommunityDataTransformer;
use App\Legacy\WebResource\Fractal\Transformer\CommunityListTransformer;
use App\Legacy\WebResource\Fractal\Transformer\CommunityTransformer;
use App\Legacy\WebResource\Fractal\Transformer\GeneralClassificationListTransformer;
use App\Legacy\WebResource\Fractal\Transformer\ImageTransformer;
use App\Legacy\WebResource\Fractal\Transformer\MatchListTransformer;
use App\Legacy\WebResource\Fractal\Transformer\MessageResultTransformer;
use App\Legacy\WebResource\Fractal\Transformer\PlayerTransformer;
use App\Legacy\WebResource\Fractal\Transformer\TokenTransformer;

/**
 * Class FractalGenerator.
 *
 * Generate resources in JSON.
 *
 * @package RJ\PronosticApp\WebResource\Fractal
 */
class FractalGenerator implements WebResourceGeneratorInterface
{

    /**
     * @var \League\Fractal\Manager
     */
    private $manager;

    /**
     * FractalGenerator constructor.
     *
     * @param Manager $manager
     */
    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
        $this->manager->setSerializer(new NoDataArraySerializer());
    }

    /**
     * @inheritDoc
     */
    public function include(string $includes)
    {
        $this->manager->parseIncludes($includes);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function exclude(string $excludes)
    {
        $this->manager->parseExcludes($excludes);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function createMessageResource(
        MessageResult $message,
        $resultType = self::JSON
    ) {
        $resource = new Item($message, MessageResultTransformer::class);
        return $this->returnResourceType(
            $this->manager->createData($resource),
            $resultType
        );
    }

    /**
     * @inheritdoc
     */
    public function createPlayerResource(
        $player,
        $resultType = FractalGenerator::JSON
    ) {
        if ($player instanceof Player) {
            $resource = new Item(
                $player,
                PlayerTransformer::class
            );
        } elseif (is_array($player)) {
            $resource = new Collection(
                $player,
                PlayerTransformer::class
            );
        } else {
            throw new \Exception("El recurso pasado no es un instancia que implemente " .
                "Player o sea un array Player[]");
        }

        return $this->returnResourceType(
            $this->manager->createData($resource),
            $resultType
        );
    }

    /**
     * @inheritdoc
     */
    public function createTokenResource(
        $token,
        $resultType = FractalGenerator::JSON
    ) {
        if ($token instanceof Token) {
            $resource = new Item(
                $token,
                TokenTransformer::class
            );
        } elseif (is_array($token)) {
            $resource = new Collection(
                $token,
                TokenTransformer::class
            );
        } else {
            throw new \Exception("El recurso pasado no es un instancia que implemente " .
                "Token o sea un array Token[]");
        }

        return $this->returnResourceType(
            $this->manager->createData($resource),
            $resultType
        );
    }

    /**
     * @inheritDoc
     */
    public function createForecastMessageResource(ForecastResult $message, $resultType = self::JSON)
    {
        $resource = new Item($message, function (ForecastResult $message) {
            return [
                'fecha_actual' => $message->getDate()->format('Y-m-d H:i:s'),
                'id_jornada' => $message->getMatchdayId(),
                'confirmados' => $message->getCorrects(),
                'errores' => $message->getErrors()
            ];
        });

        return $this->returnResourceType(
            $this->manager->createData($resource),
            $resultType
        );
    }

    /**
     * @inheritDoc
     */
    public function createCommunityResource(
        $community,
        $resultType = self::JSON
    ) {
        if ($community instanceof Community) {
            $resource = new Item($community, CommunityTransformer::class);
        } elseif (is_array($community)) {
            $resource = new Collection($community, CommunityTransformer::class);
        } else {
            throw new \Exception("El recurso pasado no es un instancia que implemente " .
                "Community o sea un array Community[]");
        }

        return $this->returnResourceType(
            $this->manager->createData($resource),
            $resultType
        );
    }

    /**
     * @inheritDoc
     */
    public function createCommunityListResource(
        $player,
        $communities,
        $resultType = self::JSON
    ) {
        if (is_array($communities)) {
            $data = new CommunityListResource($player, $communities);
            $resource = new Item($data, CommunityListTransformer::class);
        } else {
            throw new \Exception("El recurso pasado no es un array Community[]");
        }

        return $this->returnResourceType(
            $this->manager->createData($resource),
            $resultType
        );
    }

    /**
     * @inheritDoc
     */
    public function createCommunityDataResource(
        $community,
        $players,
        $matchdays,
        $matches,
        $forecasts,
        $classifications,
        $resultType = self::JSON
    ) {
        $data = new CommunityDataResource($community);
        $data->setPlayers($players);
        $data->setMatchdays($matchdays);
        $data->setMatches($matches);
        $data->setForecasts($forecasts);
        $data->setClassification($classifications);

        $resource = new Item($data, CommunityDataTransformer::class);

        return $this->returnResourceType(
            $this->manager->createData($resource),
            $resultType
        );
    }

    /**
     * @inheritDoc
     */
    public function createGeneralClassificationCommunityResource(
        $community,
        $matchdays,
        $resultType = self::JSON
    ) {
        $data = new GeneralClassificationResource($community, $matchdays);

        $resource = new Item($data, GeneralClassificationListTransformer::class);

        return $this->returnResourceType(
            $this->manager->createData($resource),
            $resultType
        );
    }


    /**
     * @inheritDoc
     */
    public function createImageResource($images, $resultType = self::JSON)
    {
        if ($images instanceof Image) {
            $resource = new Item($images, ImageTransformer::class);
        } elseif (is_array($images)) {
            $resource = new Collection($images, ImageTransformer::class);
        } else {
            throw new \Exception("El recurso pasado no es un instancia que implemente " .
                "Image o sea un array Image[]");
        }

        return $this->returnResourceType(
            $this->manager->createData($resource),
            $resultType
        );
    }

    /**
     * @inheritDoc
     */
    public function createActiveMatchesResource($matches, $resultType = self::JSON)
    {
        if (is_array($matches)) {
            $data = new MatchListResource($matches);
            $resource = new Item($data, MatchListTransformer::class);
        } else {
            throw new \Exception("El recurso pasado no un array Match[]");
        }

        return $this->returnResourceType(
            $this->manager->createData($resource),
            $resultType
        );
    }

    /**
     * @param Scope $resource
     * @param $resultType
     * @return array|string
     * @throws \Exception
     */
    private function returnResourceType(Scope $resource, $resultType)
    {
        switch ($resultType) {
            case self::JSON:
                $result = $resource->toJson();
                break;
            case self::ARRAY:
                $result = $resource->toArray();
                break;
            default:
                throw new \Exception("Tipo no soportado");
        }

        return $result;
    }
}
