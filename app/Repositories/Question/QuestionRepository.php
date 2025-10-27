<?php

namespace App\Repositories\Question;

use App\Models\Question;
use App\Models\QuestionOption;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class QuestionRepository extends BaseRepository implements QuestionRepositoryInterface
{

    protected Model $model;
    public function __construct(
         Question $model,
         protected QuestionOption $questionOptionModel,
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
            }, 'tags', 'questionOptions' => function ($query) {
                $query->withCount('userPredictions')
                    ->with('myPrediction');
            }])
            ->find($id);
    }

    public function findQuestionOptionByIdLight($id)
    {
        return $this->questionOptionModel
            ->newQuery()
            ->find($id);
    }

    public function findQuestionOptionById($id)
    {
        return $this->questionOptionModel
            ->newQuery()
            ->with('question')
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

    public function allFeedPage($params = [])
    {
        $query = $this->model
            ->newQuery()
            ->with(['tags', 'user', 'questionOptions' => function ($query) {
                $query->withCount('userPredictions');
            }])
            ->withCount(['comments', 'userPredictions', 'questionForwards']);



        $query->orderBy('id', $params['sort'] ?? 'desc');
        if (!empty($params['paginate'])) {
            return $query->paginate($params['paginate']);
        }else{

            return $query->get();
        }

    }

}
