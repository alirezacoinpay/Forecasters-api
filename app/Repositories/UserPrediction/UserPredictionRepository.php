<?php

namespace App\Repositories\UserPrediction;

use App\Models\Prediction;
use App\Models\UserPrediction;
use App\Models\PredictionLike;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class UserPredictionRepository extends BaseRepository implements UserPredictionRepositoryInterface
{

    protected Model $model;
    public function __construct(
         UserPrediction $model,
        protected Prediction $predictionModel,
        protected PredictionLike $predictionLike,
    )
    {
        parent::__construct($model);
    }

    public function findById($id)
    {
        $query = $this->model;

        if (auth()->check()) {
            $userId = auth()->id();
            $query->withCount('predictionLikes')
                  ->with(['myPredictionLike' => function ($q) use ($userId) {
                      $q->where('user_id', $userId);
                  }]);
        } else {
            $query->withCount('predictionLikes');
        }

        return $query->find($id);
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

    public function togglePredictionLike($predictionId, $userId)
    {
        $prediction = $this->predictionModel->find($predictionId);

        if (!$prediction) {
            return null;
        }

        $existingLike = $this->predictionLike->where('prediction_id', $predictionId)
            ->where('user_id', $userId)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            return false; // Unliked
        } else {
            $this->predictionLike->create([
                'prediction_id' => $predictionId,
                'user_id' => $userId,
            ]);
            return true; // Liked
        }
    }

    public function findByIdWithLikes($id, $userId = null)
    {
        $query = $this->model->withTrashed()
            ->withCount('predictionLikes');

        if ($userId) {
            $query->with(['myPredictionLike' => function ($q) use ($userId) {
                $q->where('user_id', $userId);
            }]);
        }

        return $query->find($id);
    }
    public function findByPredictionAndUser($userId, $predictionId)
    {
        $query = $this->model
            ->where('user_id', $userId)
            ->whereHas('prediction', function ($q) use ($predictionId) {
                $q->where('predictions.id', $predictionId);
            });

        return $query->first();
    }

}
