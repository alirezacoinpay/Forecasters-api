<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\UserSearchHistories\AllUserSearchHistoriesRequest;
use App\Repositories\UserSearchHistory\UserSearchHistoryRepositoryInterface;
use Illuminate\Http\JsonResponse;

class UserSearchHistoryController extends Controller
{
    public function __construct(
        protected UserSearchHistoryRepositoryInterface $repository,
    ) {}

    public function index(AllUserSearchHistoriesRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $userSearchHistories = $this->repository->all($validated);

        return $this->success($userSearchHistories);
    }
}
