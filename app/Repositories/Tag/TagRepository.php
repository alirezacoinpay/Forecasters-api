<?php

namespace App\Repositories\Tag;

use App\Models\Tag;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class TagRepository extends BaseRepository implements TagRepositoryInterface
{

    protected Model $model;
    public function __construct(
         Tag $model,
    )
    {
        parent::__construct($model);
    }

    public function findById($id)
    {
        return $this->model->withTrashed()->find($id);
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

}
