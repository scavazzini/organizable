<?php

namespace App\Providers;

use App\Repositories\CalendarRepositoryInterface;
use App\Repositories\Eloquent\EloquentCalendarRepository;
use App\Repositories\Eloquent\EloquentEventRepository;
use App\Repositories\EventRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(EventRepositoryInterface::class, EloquentEventRepository::class);
        $this->app->bind(CalendarRepositoryInterface::class, EloquentCalendarRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
