<?php

namespace App\Providers;

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
        //attendance repository binding
        $this->app->bind(
            \App\Repositories\Contracts\AttendanceRepositoryInterface::class,
            \App\Repositories\AttendanceRepository::class
        );

        //employee repository binding
        $this->app->bind(
            \App\Repositories\Contracts\EmployeeRepositoryInterface::class,
            \App\Repositories\EmployeeRepository::class
        );

        //holiday repository binding
        $this->app->bind(
            \App\Repositories\Contracts\HolidayRepositoryInterface::class,
            \App\Repositories\HolidayRepository::class
        );

        //organization repository binding
        $this->app->bind(
            \App\Repositories\Contracts\OrganizationRepositoryInterface::class,
            \App\Repositories\OrganizationRepository::class
        );

        //schedule repository binding
        $this->app->bind(
            \App\Repositories\Contracts\ScheduleRepositoryInterface::class,
            \App\Repositories\ScheduleRepository::class
        );

        //users repository binding
        $this->app->bind(
            \App\Repositories\Contracts\UserRepositoryInterface::class,
            \App\Repositories\UserRepository::class
        );

        //department repository binding
        $this->app->bind(
            \App\Repositories\Contracts\DepartmentRepositoryInterface::class,
            \App\Repositories\DepartmentRepository::class
        );
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
