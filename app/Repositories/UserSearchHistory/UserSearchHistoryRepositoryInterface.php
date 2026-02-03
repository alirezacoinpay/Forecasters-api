<?php

namespace App\Repositories\UserSearchHistory;

use App\Repositories\BaseRepositoryInterface;

interface UserSearchHistoryRepositoryInterface extends BaseRepositoryInterface
{
    public function storeFromFeed(int $userId, array $params): void;
}
