<?php

namespace App\Legacy\WebResource\Fractal\Serializer;

use League\Fractal\Serializer\ArraySerializer;

/**
 * Class NoDataArraySerializer.
 *
 * Serialize data without data key for collections.
 *
 * @package RJ\PronosticApp\WebResource\Fractal\Serializer
 */
class NoDataArraySerializer extends ArraySerializer
{
    /**
     * @inheritDoc
     */
    public function collection($resourceKey, array $data)
    {
        return $data;
    }
}
