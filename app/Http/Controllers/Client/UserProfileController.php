<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\UserProfiles\AddUserProfileRequest;
use App\Http\Requests\Client\UserProfiles\AllUserProfilesRequest;
use App\Http\Requests\Client\UserProfiles\UpdateUserProfileRequest;
use App\Http\Resources\UserProfileResource;
use App\Repositories\UserProfile\UserProfileRepositoryInterface;
use Illuminate\Http\JsonResponse;

class UserProfileController extends Controller
{
    public function __construct(
        protected UserProfileRepositoryInterface $repository,
    ) {}

    public function show($id): JsonResponse
    {
        $userProfile = $this->repository->findById($id);

        return $userProfile
            ? $this->success(new UserProfileResource($userProfile))
            : $this->error('api.not_found.userProfile', [], 404);
    }

    public function index(AllUserProfilesRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $userProfiles = $this->repository->all($validated);

        return $this->success($userProfiles);
    }

    public function store(AddUserProfileRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $userProfile = $this->repository->create($validated);

        return $this->success(new UserProfileResource($userProfile), 'api.created.userprofile');
    }

    public function update(UpdateUserProfileRequest $request, $id): JsonResponse
    {
        $validated = $request->validated();
        $userProfile = $this->repository->findByIdLight($id);

        if ($userProfile) {
            $this->repository->update($id, $validated);
            $userProfile = $this->repository->findById($id);

            return $this->success(new UserProfileResource($userProfile), 'api.updated.userprofile');
        }

        return $this->error('api.not_found.userProfile', [], 404);
    }

    public function destroy($id): JsonResponse
    {
        $result = $this->repository->delete($id);

        if ($result) {
            return $this->success($result, 'api.deleted.userprofile');
        }

        return $this->error('api.not_found.userProfile', [], 404);
    }

    public function restore($id): JsonResponse
    {
        $result = $this->repository->restore($id);

        if ($result) {
            $userProfile = $this->repository->findById($id);
            return $this->success(new UserProfileResource($userProfile), 'api.restored.userprofile');
        }

        return $this->error('api.not_found.userProfile', [], 404);
    }
}
