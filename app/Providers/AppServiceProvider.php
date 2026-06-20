<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Passport::tokensExpireIn(now()->addMinutes((int) config('passport.token_expiration_minutes', 30)));
        Passport::personalAccessTokensExpireIn(now()->addMinutes((int) config('passport.token_expiration_minutes', 30)));
    }
}
