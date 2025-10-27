<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens;
    public const string TAG = "user";

    protected $fillable = [
        'username',
        'mobile',
        'mobile_verified_at',
    ];
    public function userPredictions(): HasMany
    {
        return $this->hasMany(UserPrediction::class);
    }
}
