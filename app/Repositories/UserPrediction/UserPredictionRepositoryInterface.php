<?php

namespace App\Repositories\UserPrediction;

use App\Repositories\BaseRepositoryInterface;

interface UserPredictionRepositoryInterface extends BaseRepositoryInterface
{
    public function togglePredictionLike($questionId, $userId);
    public function findByIdWithLikes($id, $userId = null);
}
