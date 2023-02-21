<?php

namespace App\Providers;


use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('authorizedToViewOcr',function(User $user){
            if ($user->hasRole('Admin') || $user->hasAnyPermission(['ocr','ocr_create','ocr_edit','ocr_delete','ocr_show'])) {
                return true;
            }
            return false;
        });
    }
}
