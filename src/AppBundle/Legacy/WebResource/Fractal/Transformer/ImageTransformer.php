<?php

namespace AppBundle\Legacy\WebResource\Fractal\Transformer;

use AppBundle\Entity\Image;
use League\Fractal\TransformerAbstract;

/**
 * Class ImageTransformer.
 *
 * @package RJ\PronosticApp\WebResource\Fractal\Transformer
 */
class ImageTransformer extends TransformerAbstract
{
    /**
     * @param Image $image
     * @return array
     */
    public function transform(Image $image)
    {
        return [
            'id' => $image->getId(),
            'url' => $image->getUrl()
        ];
    }
}
