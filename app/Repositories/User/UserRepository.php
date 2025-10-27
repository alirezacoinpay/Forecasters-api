<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{

    protected Model $model;
    public function __construct(
         User $model,
    )
    {
        parent::__construct($model);
    }

    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function findByIdLight($id)
    {
        return $this->model->find($id);
    }

    public function findByMobileLight($mobile)
    {
        return $this->model
            ->where('mobile', $mobile)
            ->first();
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
