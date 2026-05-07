<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Domain;
use App\Models\Setting;
use App\Models\Tenant;
use App\Models\User;
use App\Models\CompanySetting;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

use App\Observers\DomainObserver;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (! $this->app->runningInConsole() && request()->hasHeader('host')) {
            $host = request()->getHost();
            config(['session.cookie' => Str::slug($host, '_').'_session']);
        }

        Domain::observe(DomainObserver::class);

        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        // setting table value share
        $companysetting = CompanySetting::first();

        if ($companysetting) {
            View::share('companysetting', $companysetting);
        }
    }
}
