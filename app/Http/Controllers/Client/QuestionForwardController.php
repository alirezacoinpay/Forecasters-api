<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\QuestionForwards\AddQuestionForwardRequest;
use App\Http\Requests\Client\QuestionForwards\AllQuestionForwardsRequest;
use App\Http\Requests\Client\QuestionForwards\UpdateQuestionForwardRequest;
use App\Http\Resources\QuestionForwardResource;
use App\Repositories\QuestionForward\QuestionForwardRepositoryInterface;
use Illuminate\Http\JsonResponse;

class QuestionForwardController extends Controller
{
    public function __construct(
        protected QuestionForwardRepositoryInterface $repository,
    ) {}

    public function show($id): JsonResponse
    {
        $questionForward = $this->repository->findById($id);

        return $questionForward
            ? $this->success(new QuestionForwardResource($questionForward))
            : $this->error('api.not_found.questionForward', [], 404);
    }

    public function index(AllQuestionForwardsRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $questionForwards = $this->repository->all($validated);

        return $this->success($questionForwards);
    }

    public function store(AddQuestionForwardRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $questionForward = $this->repository->create($validated);

        return $this->success(new QuestionForwardResource($questionForward), 'api.created.questionforward');
    }

    public function update(UpdateQuestionForwardRequest $request, $id): JsonResponse
    {
        $validated = $request->validated();
        $questionForward = $this->repository->findByIdLight($id);

        if ($questionForward) {
            $this->repository->update($id, $validated);
            $questionForward = $this->repository->findById($id);

            return $this->success(new QuestionForwardResource($questionForward), 'api.updated.questionforward');
        }

        return $this->error('api.not_found.questionForward', [], 404);
    }

    public function destroy($id): JsonResponse
    {
        $result = $this->repository->delete($id);

        if ($result) {
            return $this->success($result, 'api.deleted.questionforward');
        }

        return $this->error('api.not_found.questionForward', [], 404);
    }

    public function restore($id): JsonResponse
    {
        $result = $this->repository->restore($id);

        if ($result) {
            $questionForward = $this->repository->findById($id);
            return $this->success(new QuestionForwardResource($questionForward), 'api.restored.questionforward');
        }

        return $this->error('api.not_found.questionForward', [], 404);
    }
}
