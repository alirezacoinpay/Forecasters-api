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

    public function userFeedQuestion($id, $userId = null)
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

    public function userFeedQuestions($userId = null, $params = [])
    {
        $query = $this->model
            ->newQuery()
            ->with(['tags', 'user', 'comments', 'questionOptions' => function ($query) {
                $query->withCount('userPredictions');
            }])
            ->withCount(['comments', 'userPredictions', 'questionForwards']);

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

    public function userSearchQuestions($userId = null, $params = [])
    {
        $query = $this->model
            ->newQuery()
            ->with(['tags', 'user', 'comments', 'questionOptions' => function ($query) {
                $query->withCount('userPredictions');
            }])
            ->withCount(['comments', 'userPredictions', 'questionForwards']);

        //TODO
        if (isset($params['search'])) {
            $query->where('text', $params['search']);
        }

        if (isset($params['tag_id'])) {
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
