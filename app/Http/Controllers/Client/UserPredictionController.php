<?php

namespace App\Http\Controllers\Client;

use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\UserPredictions\AddUserPredictionRequest;
use App\Http\Requests\Client\UserPredictions\AllUserPredictionsRequest;
use App\Http\Requests\Client\UserPredictions\UpdateUserPredictionRequest;
use App\Http\Resources\Client\UserPredictionResource;
use App\Models\Banner;
use App\Models\Comment;
use App\Repositories\Comment\CommentRepositoryInterface;
use App\Repositories\Prediction\PredictionRepositoryInterface;
use App\Repositories\UserPrediction\UserPredictionRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserPredictionController extends Controller
{
    public function __construct(
        protected UserPredictionRepositoryInterface $repository,
        protected CommentRepositoryInterface $commentRepository,
        protected PredictionRepositoryInterface $predictionRepository,
    ) {
    }

    public function show($id): JsonResponse
    {
        $userPrediction = $this->repository->findById($id);

        return $userPrediction
            ? $this->success(new UserPredictionResource($userPrediction))
            : $this->error('api.not_found.userPrediction', [], 404);
    }

    public function index(AllUserPredictionsRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $userPredictions = $this->repository->all($validated);

        return $this->success($userPredictions);
    }

    public function store(AddUserPredictionRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $predictionOption = $this->predictionRepository->findPredictionOptionByIdLight($validated['prediction_option_id']);

        if (!$predictionOption) {

            return $this->error('api.predictionOption.not_found', [], 404);
        }
        $user = Auth::user();
        $userPrediction = $this->repository->create([
            'user_id' => $user->getAuthIdentifier(),
            'prediction_option_id' => $predictionOption->id,
            'percentage' => 100,
        ]);
        if (!empty($validated['comment'])) {
            $commentData = [
                'user_id' => $user->getAuthIdentifier(),
                'prediction_id' => $predictionOption->prediction_id,
                'text' => $validated['comment']['text'],
            ];
            if (isset($validated['comment']['file'])) {

                $commentData['file'] = FileHelper::uploadFile($validated['comment']['file'], Comment::FILE_PATH);
            }
            $this->commentRepository->create($commentData);
        }

        return $this->success(new UserPredictionResource($userPrediction), 'api.created.userPrediction');
    }

    public function update(UpdateUserPredictionRequest $request, $id): JsonResponse
    {
        $validated = $request->validated();
        $userPrediction = $this->repository->findByIdLight($id);

        if ($userPrediction) {
            $this->repository->update($id, $validated);
            $userPrediction = $this->repository->findById($id);

            return $this->success(new UserPredictionResource($userPrediction), 'api.updated.userPrediction');
        }

        return $this->error('api.not_found.userPrediction', [], 404);
    }

    public function destroy($id): JsonResponse
    {
        $result = $this->repository->delete($id);

        if ($result) {
            return $this->success($result, 'api.deleted.userPrediction');
        }

        return $this->error('api.not_found.userPrediction', [], 404);
    }
}
