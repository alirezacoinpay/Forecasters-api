<?php

namespace App\Repositories\UserPrediction;

use App\Repositories\BaseRepositoryInterface;

interface UserPredictionRepositoryInterface extends BaseRepositoryInterface
{
    public function togglePredictionLike($predictionId, $userId);
    public function findByIdWithLikes($id, $userId = null);
    public function findByPredictionAndUser($userId, $predictionId);
}
