<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Telegram\TelegramAuthService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
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
        Log::info('TelegramAuthController::login', [
            'step3' => true,
            '$telegramData' => $telegramData,
        ]);
        if (!$telegramData) {

            return $this->error('Invalid Telegram authentication.', [], 401);
        }

        $telegramUser = $telegramData['user'];
        Log::info('TelegramAuthController::login', [
            'step4' => true,
            '$telegramUser' => $telegramUser,
        ]);
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
        $user->userProfile()->updateOrCreate([
            'avatar' => $telegramUser['photo_url'] ?? null,
            'name' => trim(
                ($telegramUser['first_name'] ?? '') .
                ' ' .
                ($telegramUser['last_name'] ?? ''))
        ]);
        Log::info('TelegramAuthController::login', [
            'step5' => true,
            '$telegramUser' => $user,
        ]);

        $token = $user->createToken('clientToken')->plainTextToken;
        Log::info('TelegramAuthController::login', [
            'step6' => true,
            '$token' => $token,
        ]);
        $cookie = $this->createCookie($token);
        Log::info('TelegramAuthController::login', [
            'step7' => true,
            '$cookie' => $cookie,
        ]);
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
