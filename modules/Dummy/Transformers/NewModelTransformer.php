<?php

namespace Modules\Dummy\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\Dummy\Entities\NewModel;

/**
 * Class NewModelTransformer
 * @package Dummy
 */
class NewModelTransformer extends TransformerAbstract
{

    /**
     * Transform the \NewModel entity
     * @param \Modules\Dummy\Entities\NewModel $model
     *
     * @return array
     */
    public function transform(NewModel $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
