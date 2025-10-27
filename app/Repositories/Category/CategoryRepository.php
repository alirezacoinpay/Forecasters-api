<?php

namespace App\Repositories\Category;

use App\Enums\CategoryStatus;
use App\Models\Category;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{

    protected Model $model;
    public function __construct(
         Category $model,
    )
    {
        parent::__construct($model);
    }

    public function findById($id)
    {
        return $this->model->find($id);
    }


    public function all($params = [])
    {
        $query = $this->model->newQuery();


        $query->orderBy('id', $params['sort'] ?? 'desc');
        if (!empty($params['paginate'])) {
            return $query->paginate($params['paginate']);
        }else{

            return $query->get();
        }

    }

    public function allFeedPage($params = [])
    {
        $query = $this->model->newQuery()
        ->select(['title', 'icon', 'id'])
        ->where('status', CategoryStatus::ACTIVE);


        $query->orderBy('id', $params['sort'] ?? 'desc');
        if (!empty($params['paginate'])) {
            return $query->paginate($params['paginate']);
        }else{

            return $query->get();
        }

    }

}
