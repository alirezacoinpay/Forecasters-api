<?php

namespace App\Repositories\Prediction;

use App\Repositories\BaseRepositoryInterface;

interface PredictionRepositoryInterface extends BaseRepositoryInterface
{
    public function insertPredictionOptions($data);
    public function findPredictionOptionById($id);
    public function findPredictionOptionByIdLight($id);
    public function findFeedPage($id);
    public function userFeedPrediction($id, $userId = null);
    public function userFeedPredictions($userId = null, $params = []);
    public function userSearchPredictions($userId = null, $params = []);
}
