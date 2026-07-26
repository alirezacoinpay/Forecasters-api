<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Telegram\TelegramAuthService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Cookie;


class TelegramAuthController extends Controller
{
    public function __construct(
        protected UserRepositoryInterface $repository,
    ) {}

    public function login(Request $request, TelegramAuthService $telegram): JsonResponse
    {
        $request->validate([
            'initData' => ['required', 'string'],
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

        $profile = $user->userProfile()->firstOrCreate([]);

        if (! $profile->avatar) {
            $profile->avatar = $this->getTelegramUserAvatar($telegramUser['photo_url']);
        }

        $profile->name = trim(
            ($telegramUser['first_name'] ?? '') . ' ' . ($telegramUser['last_name'] ?? '')
        );

        $profile->save();


        $token = $user->createToken('clientToken')->plainTextToken;

        $cookie = $this->createCookie($token);

        return $this->success([
            'user' => $user,
        ], 'telegram.login.success')->withCookie($cookie);
    }

    public function getTelegramUserAvatar(?string $url): ?string
    {
        if (blank($url)) {
            return null;
        }

        try {
            $response = Http::timeout(20)
                ->accept('image/*')
                ->get($url);

            if (! $response->successful()) {
                return $url;
            }

            $contentType = $response->header('Content-Type');

            if (! str_starts_with($contentType, 'image/')) {
                return $url;
            }

            $extension = match ($contentType) {
                'image/svg+xml' => 'svg',
                'image/png' => 'png',
                'image/jpeg' => 'jpg',
                'image/webp' => 'webp',
                default => 'bin',
            };

            $filename = Str::uuid().'.'.$extension;

            Storage::disk('public')->put(
                UserProfile::FILE_PATH.'/'.$filename,
                $response->body()
            );

            return $filename;
        } catch (\Throwable $e) {
            report($e);

            return $url;
        }
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
