<?php

namespace App\Providers;
use Carbon\Carbon;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

        $this->registerPolicies();
        Passport::hashClientSecrets();
        Passport::enablePasswordGrant();
        Passport::tokensExpireIn(Carbon::now()->addDays(30));

        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
    }
}

