<?php

namespace App\Filament\Auth;

use Filament\Auth\Pages\Login;
use Filament\Forms\Concerns\InteractsWithForms;

class AdminLogin extends Login
{
    public function getTitle(): string
    {
        return 'Hi Admin';
    }

}
