<?php

namespace Modules\Dummy\Repositories;

use RepositoryLab\Repository\Eloquent\BaseRepository;
use RepositoryLab\Repository\Criteria\RequestCriteria;
use Modules\Dummy\Entities\NewModel;

/**
 * Class NewModelRepositoryEloquent
 * @package Dummy
 */
class NewModelRepositoryEloquent extends BaseRepository implements NewModelRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return NewModel::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
