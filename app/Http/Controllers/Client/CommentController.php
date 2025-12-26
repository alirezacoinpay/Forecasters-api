<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Comments\AddCommentRequest;
use App\Http\Requests\Client\Comments\UpdateCommentRequest;
use App\Http\Resources\Client\CommentResource;
use App\Repositories\Comment\CommentRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct(
        protected CommentRepositoryInterface $repository,
    ) {}

    public function show($id): JsonResponse
    {
        $comment = $this->repository->findById($id);

        return $comment
            ? $this->success(new CommentResource($comment))
            : $this->error('api.not_found.comment', [], 404);
    }

    public function store(AddCommentRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::user()->getAuthIdentifier();
        $comment = $this->repository->create($validated);

        return $this->success(new CommentResource($comment), 'api.created.comment');
    }

    public function update(UpdateCommentRequest $request, $id): JsonResponse
    {
        $validated = $request->validated();
        $comment = $this->repository->findByIdLight($id);

        if ($comment) {
            $this->repository->update($id, $validated);
            $comment = $this->repository->findById($id);

            return $this->success(new CommentResource($comment), 'api.updated.comment');
        }

        return $this->error('api.not_found.comment', [], 404);
    }

    public function destroy($id): JsonResponse
    {
        $result = $this->repository->delete($id);

        if ($result) {
            return $this->success($result, 'api.deleted.comment');
        }

        return $this->error('api.not_found.comment', [], 404);
    }
}
