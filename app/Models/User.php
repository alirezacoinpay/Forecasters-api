<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens;
    public const string TAG = "user";

    protected $fillable = [
        'ip',
        'username',
        'mobile',
        'mobile_verified_at',
        'telegram_token',
    ];
    public function userPredictions(): HasMany
    {
        return $this->hasMany(UserPrediction::class);
    }
    public function userProfile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }
}
