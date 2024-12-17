<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

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
        Gate::define('only-admin-access', function ($user) {
            return $user->user_type == User::$ADMIN;
        });
        Gate::define('only-doctor-access', function ($user) {
            return $user->user_type == User::$DOCTOR;
        });
        Gate::define('only-staff-access', function ($user) {
            return $user->user_type == User::$STAFF;
        });
        Gate::define('admin-staff-access', function ($user, ?string $accessPage = null, ?string $accessType = null) {
            if ($user->user_type == User::$ADMIN){
                return true;
            } else if ($user->user_type == User::$STAFF) {
                $staffAccess = $user->staffProfile->staffAccess->where('categories', $accessPage)->first();
                return $staffAccess->{$accessType};
            }
            return false;
        });
        Gate::define('doctor-admin-access', function ($user) {
            return $user->user_type == User::$ADMIN || $user->user_type == User::$DOCTOR;
        });
        Gate::define('admin-staff-doctor-access', function ($user, ?string $accessPage = null, ?string $accessType = null) {
            if ($user->user_type == User::$ADMIN){
                return true;
            } else if ($user->user_type == User::$STAFF) {
                $staffAccess = $user->staffProfile->staffAccess->where('categories', $accessPage)->first();
                return $staffAccess->{$accessType};
            } else if ($user->user_type == User::$DOCTOR) {
                return true;
            }
        });
    }
    
}
