<?php

namespace App\Repositories\Topic;

use App\Models\Topic;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class TopicRepository extends BaseRepository implements TopicRepositoryInterface
{

    protected Model $model;
    public function __construct(
         Topic $model,
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
