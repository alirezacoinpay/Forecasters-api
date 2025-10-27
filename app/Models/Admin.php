<?php

namespace App\Models;

use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Filament\Models\Contracts\FilamentUser;

class Admin extends Authenticatable implements FilamentUser
{
    use HasFactory;

    protected $fillable = [
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function getNameAttribute(): string
    {
        return 'admin';
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
}
