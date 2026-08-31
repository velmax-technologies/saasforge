<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Support\Tenant\CurrentOrganization;
use Illuminate\Support\Facades\Gate;
use App\Models\Organization;
use App\Models\User;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            CurrentOrganization::class,
            fn () => new CurrentOrganization()
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('organization.manage', function (
            User $user,
            Organization $organization
        ) {
            return $user->hasPermission(
                'organization.manage',
                $organization
            );
        });

        Gate::define('members.view', function (
            User $user,
            Organization $organization
        ) {
            return $user->hasPermission(
                'members.view',
                $organization
            );
        });

        Gate::define('members.manage', function (
            User $user,
            Organization $organization
        ) {
            return $user->hasPermission(
                'members.manage',
                $organization
            );
        });

        Gate::define('billing.view', function (
            User $user,
            Organization $organization
        ) {
            return $user->hasPermission(
                'billing.view',
                $organization
            );
        });

        Gate::define('billing.manage', function (
            User $user,
            Organization $organization
        ) {
            return $user->hasPermission(
                'billing.manage',
                $organization
            );
        });
    }
}
