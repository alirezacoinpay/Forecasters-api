<?php

namespace App\Repositories\UserPrediction;

use App\Models\UserPrediction;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class UserPredictionRepository extends BaseRepository implements UserPredictionRepositoryInterface
{

    protected Model $model;
    public function __construct(
         UserPrediction $model,
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



        if (isset($params['trashed'])) {
            if ($params['trashed']) {
                $query->onlyTrashed();
            }
        }else{
            $query->withTrashed();
        }

        $query->orderBy('id', $params['sort'] ?? 'desc');
        if (!empty($params['paginate'])) {
            return $query->paginate($params['paginate']);
        }else{

            return $query->get();
        }

    }

}
