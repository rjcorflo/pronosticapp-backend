<?php

namespace App\Legacy\WebResource\Fractal\Transformer;

use App\Legacy\Util\General\MessageItem;
use App\Legacy\Util\General\MessageResult;
use League\Fractal\TransformerAbstract;

/**
 * Class MessageResultTransformer
 * @package RJ\PronosticApp\WebResource\Fractal\Transformer
 */
class MessageResultTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include.
     *
     * @var array
     */
    protected $defaultIncludes = [
        'mensajes'
    ];

    /**
     * @param MessageResult $messageResult
     * @return array
     */
    public function transform(MessageResult $messageResult)
    {
        return [
            'error'      => (bool) $messageResult->hasError(),
            'descripcion'   => $messageResult->getDescription()
        ];
    }

    /**
     * Include messages.
     *
     * @param MessageResult $message
     * @return \League\Fractal\Resource\Collection
     */
    public function includeMensajes(MessageResult $message)
    {
        $items = $message->getMessages();

        return $this->collection($items, function (MessageItem $item) {
            return [
                'code' => $item->getCode(),
                'obs' => $item->getObservation()
            ];
        });
    }
}
