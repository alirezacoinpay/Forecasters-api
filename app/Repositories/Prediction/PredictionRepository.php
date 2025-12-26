<?php

namespace App\Repositories\Prediction;

use App\Models\Prediction;
use App\Models\PredictionOption;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class PredictionRepository extends BaseRepository implements PredictionRepositoryInterface
{

    protected Model $model;
    public function __construct(
         Prediction $model,
         protected PredictionOption $predictionOptionModel,
    )
    {
        parent::__construct($model);
    }

    public function findById($id)
    {
        return $this->model->withTrashed()->find($id);
    }

    public function findFeedPage($id)
    {
        return $this->model
            ->newQuery()
            ->withCount(['userPredictions', 'comments'])
            ->with(['comments' => function ($query) {
                $query->with(['user'])
                ->withCount(['children']);
            }, 'tags', 'predictionOptions' => function ($query) {
                $query->withCount('userPredictions')
                    ->with('myPrediction');
            }])
            ->find($id);
    }

    public function userFeedPrediction($id, $userId = null)
    {
        return $this->model
            ->newQuery()
            ->with(['userPrediction' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }])
            ->withCount(['userPredictions', 'comments'])
            ->with(['comments' => function ($query) {
                $query->with(['user'])
                ->withCount(['children']);
            }, 'tags', 'predictionOptions' => function ($query) {
                $query->withCount('userPredictions')
                    ->with('myPrediction');
            }])
            ->find($id);
    }

    public function findPredictionOptionByIdLight($id)
    {
        return $this->predictionOptionModel
            ->newQuery()
            ->find($id);
    }

    public function findPredictionOptionById($id)
    {
        return $this->predictionOptionModel
            ->newQuery()
            ->with('prediction')
            ->find($id);
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

    public function userFeedPredictions($userId = null, $params = [])
    {
        $query = $this->model
            ->newQuery()
            ->with(['tags', 'user', 'comments.commentLikes', 'predictionOptions' => function ($query) {
                $query->withCount('userPredictions');
            }])
            ->withCount(['comments', 'userPredictions', 'predictionForwards']);

        if (isset($params['topic_id'])) {
            $query->where('topic_id', $params['topic_id']);
        }

        $query->orderBy('id', $params['sort'] ?? 'desc');
        if (!empty($params['paginate'])) {
            return $query->paginate($params['paginate']);
        }else{

            return $query->get();
        }

    }

    public function userSearchPredictions($userId = null, $params = [])
    {
        $query = $this->model
            ->newQuery()
            ->with(['tags', 'user', 'comments', 'predictionOptions' => function ($query) {
                $query->withCount('userPredictions');
            }])
            ->withCount(['comments', 'userPredictions', 'predictionForwards']);

        //TODO
        if (isset($params['search'])) {
            $query->where('text', $params['search']);
        }

        if (isset($params['tag_id'])) {

            $query->whereHas('tags' , function ($query) use ($params) {
                $query->where('tags.id', $params['tag_id']);
            });
        }

        if (isset($params['topic_id'])) {
            $query->where('topic_id', $params['topic_id']);
        }

        $query->orderBy('id', $params['sort'] ?? 'desc');
        if (!empty($params['paginate'])) {
            return $query->paginate($params['paginate']);
        }else{

            return $query->get();
        }

    }

}
