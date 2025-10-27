<?php

namespace App\Repositories\Question;

use App\Repositories\BaseRepositoryInterface;

interface QuestionRepositoryInterface extends BaseRepositoryInterface
{
    public function findQuestionOptionById($id);
    public function findQuestionOptionByIdLight($id);
    public function findFeedPage($id);
    public function allFeedPage($params = []);
}
