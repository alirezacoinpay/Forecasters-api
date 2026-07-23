<?php
namespace App\Http\Controllers\Client;

use App\Enums\SmsTemplate;
use App\Facades\Sms;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Auth\LoginRequest;
use App\Http\Requests\Client\Auth\SendOtpRequest;
use App\Http\Requests\Client\Auth\VerifyOtpRequest;
use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Telegram\TelegramAuthService;
use Illuminate\Http\Client\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Cookie;


class TelegramAuthController extends Controller
{
    public function __construct(
        protected UserRepositoryInterface $repository,
    ) {}

    public function login(Request $request, TelegramAuthService $telegram): JsonResponse
    {

        Log::info('TelegramAuthController::login', [
            'step1' => true,
        ]);
        $request->validate([
            'initData' => ['required', 'string'],
        ]);
        Log::info('TelegramAuthController::login', [
            'step2' => true,
            'initData' => $request->initData,
            'initData2' => $request['initData'],
        ]);
        $telegramData = $telegram->validate($request->initData);

        if (!$telegramData) {

            return $this->error('Invalid Telegram authentication.', [], 401);
        }

        $telegramUser = $telegramData['user'];

        $user = User::firstOrCreate(
            [
                'telegram_token' => $telegramUser['id'],
            ],
            [
                'username' => trim(
                    ($telegramUser['first_name'] ?? '') .
                    ' ' .
                    ($telegramUser['last_name'] ?? '')
                ),
            ]
        );


        $token = $user->createToken('clientToken')->plainTextToken;

        $cookie = $this->createCookie($token);

        return $this->success([
            'user' => $user,
        ], 'telegram.login.success')->withCookie($cookie);
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
