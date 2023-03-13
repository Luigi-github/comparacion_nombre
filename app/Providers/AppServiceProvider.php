<?php

namespace App\Providers;

use App\Contracts\MatchSearchService;
use App\Repositories\MatchSearchRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(MatchSearchService::class, MatchSearchRepository::class);
    }
}
