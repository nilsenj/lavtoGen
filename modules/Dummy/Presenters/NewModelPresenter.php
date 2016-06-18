<?php

namespace Modules\Dummy\Presenters;

use Modules\Dummy\Transformers\NewModelTransformer;
use RepositoryLab\Repository\Presenter\FractalPresenter;

/**
 * Class NewModelPresenter
 *
 * @package Dummy
 */
class NewModelPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new NewModelTransformer();
    }
}
