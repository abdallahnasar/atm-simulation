<?php

namespace App\Providers;

use App\Models\User;
use App\Services\LoginService;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        $this->app->bind(LoginService::class, function ($app) {
            return new LoginService(
                $app->make(User::class),
                $app->make('hash')
            );
        });

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Passport::personalAccessTokensExpireIn(now()->addMinutes(15));
    }
}
