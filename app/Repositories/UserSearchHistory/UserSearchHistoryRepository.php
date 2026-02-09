<?php

namespace App\Repositories\UserSearchHistory;

use App\Enums\SearchType;
use App\Models\Tag;
use App\Models\UserSearchHistory;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class UserSearchHistoryRepository extends BaseRepository implements UserSearchHistoryRepositoryInterface
{

    protected Model $model;
    public function __construct(
         UserSearchHistory $model,
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
    public function userSearchPredictions($userId, $params = [])
    {
        $query = $this->model->newQuery();
        $query->where('user_id', $userId);

        $query->orderBy('id', $params['sort'] ?? 'desc');
        if (!empty($params['paginate'])) {
            return $query->paginate($params['paginate']);
        }else{

            return $query->get();
        }

    }

    public function storeFromFeed(int $userId, array $params): void
    {
        if (! empty($params['search'])) {
            $this->storeTextSearch($userId, $params['search']);
        }

        if (! empty($params['tag_id'])) {
            $this->storeModelSearch(
                userId: $userId,
                model: Tag::class,
                modelId: $params['tag_id'],
                type: SearchType::TAG
            );
        }
    }

    protected function storeTextSearch(int $userId, string $text): void
    {
        $this->model->newQuery()->create([
            'user_id'     => $userId,
            'search_type' => SearchType::TEXT,
            'search_text' => $text,
        ]);
    }

    protected function storeModelSearch(
        int $userId,
        string $model,
        int $modelId,
        SearchType $type
    ): void {

        $this->model->newQuery()->create([
            'user_id'         => $userId,
            'search_type'     => $type,
            'searchable_type'=> $model,
            'searchable_id'  => $modelId,
        ]);
    }

}
