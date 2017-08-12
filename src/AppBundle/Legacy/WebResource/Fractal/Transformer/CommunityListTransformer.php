<?php

namespace AppBundle\Legacy\WebResource\Fractal\Transformer;

use AppBundle\Legacy\WebResource\Fractal\Resource\CommunityListResource;
use League\Fractal\TransformerAbstract;

/**
 * Class CommunityListTransformer.
 *
 * @package RJ\PronosticApp\WebResource\Fractal\Transformer
 */
class CommunityListTransformer extends TransformerAbstract
{
    /**
     * List of default resources for including.
     *
     * @var array
     */
    protected $defaultIncludes = [
        'comunidades'
    ];

    /**
     * @param  CommunityListResource $communityList
     * @return array
     */
    public function transform(CommunityListResource $communityList)
    {
        $item = [
            'fecha_actual' => $communityList->getActualDate()->format('Y-m-d H:i:s')
        ];

        return $item;
    }

    /**
     * Include Comunidades.
     *
     * @param CommunityListResource $communityList
     * @return \League\Fractal\Resource\Collection
     */
    public function includeComunidades(CommunityListResource $communityList)
    {
        return $this->collection(
            $communityList->getPlayerCommunities(),
            PlayerCommunityTransformer::class
        );
    }
}
