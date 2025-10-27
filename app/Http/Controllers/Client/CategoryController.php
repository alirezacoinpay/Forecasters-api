<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Categories\AllCategoriesRequest;
use App\Http\Resources\Client\CategoryResource;
use App\Repositories\Category\CategoryRepositoryInterface;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryRepositoryInterface $repository,
    ) {}

    public function show($id): JsonResponse
    {
        $category = $this->repository->findById($id);

        return $category
            ? $this->success(new CategoryResource($category))
            : $this->error('api.not_found.category', [], 404);
    }

    public function index(AllCategoriesRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $categories = $this->repository->allFeedPage($validated);

        return $this->success($categories);
    }
}
