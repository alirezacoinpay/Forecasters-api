<?php
namespace App\Http\Controllers\Client;

use App\Enums\SmsTemplate;
use App\Facades\Sms;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Auth\SendOtpRequest;
use App\Http\Requests\Client\Auth\VerifyOtpRequest;
use App\Http\Requests\Client\User\EditProfileRequest;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    public function __construct(
        protected UserRepositoryInterface $repository,
    ) {}

    public function me() : JsonResponse
    {
        $user = auth()->user();
        return $this->success(['user' => $user]);
    }

    public function editProfile(EditProfileRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $user = auth()->user();
        $this->repository->update($user->getAuthIdentifier(), $validated);
        $user = $this->repository->findById($user->getAuthIdentifier());

        return $this->success($user, 'api.user.update.success');
    }
}
