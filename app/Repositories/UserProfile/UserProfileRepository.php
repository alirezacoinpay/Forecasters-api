<?php

namespace App\Repositories\UserProfile;

use App\Models\UserProfile;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class UserProfileRepository extends BaseRepository implements UserProfileRepositoryInterface
{

    protected Model $model;
    public function __construct(
         UserProfile $model,
    )
    {
        parent::__construct($model);
    }

    public function findById($id)
    {
        return $this->model
            ->newQuery()
            ->find($id);
    }
    public function findByUserId($userId)
    {
        return $this->model
            ->newQuery()
            ->where('user_id', $userId)
            ->first();
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
