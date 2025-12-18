<?php
namespace App\Http\Controllers\Client;

use App\Enums\SmsTemplate;
use App\Facades\Sms;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Auth\LoginRequest;
use App\Http\Requests\Client\Auth\SendOtpRequest;
use App\Http\Requests\Client\Auth\VerifyOtpRequest;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Cookie;


class AuthController extends Controller
{
    public function __construct(
        protected UserRepositoryInterface $repository,
    ) {}

    public function login(LoginRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = $this->repository->create($validated);
        $token = $user->createToken('clientToken')->plainTextToken;

        $cookie = $this->createCookie($token);
        return $this->success(['user' => $user], 'api.otp.verified')->withCookie($cookie);
    }

    public function sendOtp(SendOtpRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = $this->repository->findByMobileLight($validated['mobile']);
        if (!$user) {
            $user = $this->repository->create($validated);
        }
        $otpCode = $this->checkOtpAndCreate($user->mobile);
        if ($otpCode){

            $result = Sms::sendOtp($user->mobile, $otpCode, SmsTemplate::LOGIN->value);
//            if ($result['success']){

                return $this->success([
                    'otp' => $otpCode,
                ], 'api.otp.send');
//            }else{
//                return $this->error($result['message']);
//            }
        }
        return $this->error('api.otp.not_expired_please_wait');
    }

    public function verifyOtp(VerifyOtpRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = $this->repository->findByMobileLight($validated['mobile']);
        if (!$user) {

            return $this->error('api.user.not_found');
        }

        if ($this->verifyOtpCode($user->mobile, (string) $validated['otp'])){

            $token = $user->createToken('clientToken')->plainTextToken;
            $this->deleteOtp($user->mobile);
            return $this->success(['token' => $token, 'user' => $user], 'api.otp.verified');
        }
        return $this->error('api.otp.not_expired_please_wait');
    }


    private function checkOtpAndCreate($mobile): false|string
    {
        $oldOtp = Cache::get($mobile);
        if ($oldOtp) {
            return false;
        }

        $otp = random_int(10000, 99999);

        Cache::put($mobile, $otp, now()->addMinutes(3));
        return $otp;
    }

    private function verifyOtpCode($mobile, string $otp): bool
    {
        $cacheOtp = Cache::get($mobile);
        if ($cacheOtp && $otp === (string) $cacheOtp) {
            return true;
        }
        return false;
    }
    private function deleteOtp($mobile): void
    {
        Cache::forget($mobile);
    }

    private function createCookie(string $token): Cookie
    {
        return cookie(
            name: 'auth_user',
            value: $token,
            minutes: 60 * 24 * 365,
            path: '/',
            domain: null,
            secure: true,
            httpOnly: true,
            raw: false,
            sameSite: env('production') ? 'Lax' : 'None'
        );
    }
}
