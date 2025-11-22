<?php

namespace App\Providers;

use App\Support\HospitalConfig;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(HospitalConfig::class, fn () => new HospitalConfig());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $hospital = app(HospitalConfig::class)->get();
            $view->with('hospitalConfig', $hospital);
        });
    }
}
