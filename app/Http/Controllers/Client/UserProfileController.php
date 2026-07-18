<?php

namespace App\Http\Controllers\Client;

use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\UserProfiles\AddUserProfileRequest;
use App\Http\Requests\Client\UserProfiles\AllUserProfilesRequest;
use App\Http\Requests\Client\UserProfiles\UpdateUserProfileRequest;
use App\Http\Resources\Client\UserResource;
use App\Http\Resources\UserProfileResource;
use App\Models\UserProfile;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\UserProfile\UserProfileRepositoryInterface;
use Illuminate\Http\JsonResponse;

class UserProfileController extends Controller
{
    protected ?int $userId;
    public function __construct(
        protected UserProfileRepositoryInterface $repository,
        protected UserRepositoryInterface $userRepository,
    ) {
        $this->userId = auth()->user()?->getAuthIdentifier() ?? null;
    }

    public function update(UpdateUserProfileRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $this->updateOrCreateUserProfile($validated);
        $this->userRepository->update($this->userId, $validated);
        $user = $this->userRepository->findById($this->userId);

        return $this->success(new UserResource($user), 'api.updated.userprofile');
    }

    public function updateOrCreateUserProfile($validated): void
    {
        $userProfile = $this->repository->findByUserId($this->userId);
        if (isset($validated['avatar'])) {
            $validated['avatar'] = FileHelper::uploadFile($validated['avatar'], UserProfile::FILE_PATH.'/avatars');
        }
        if ($userProfile) {

            FileHelper::deleteFile($userProfile->avatar);
            $this->repository->update($userProfile->id, $validated);
        }else{
            $validated['userId'] = $this->userId;
            $this->repository->create($validated);
        }
    }
}
