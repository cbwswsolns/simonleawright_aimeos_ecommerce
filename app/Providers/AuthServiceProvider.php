<?php

namespace App\Providers;

use App\Models\Page;
use App\Policies\PagePolicy;
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
        Page::class => PagePolicy::class,
    ];


    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /* CBW: lines below required for aimeos laravel package
           See https://github.com/aimeos/aimeos-laravel */
        Gate::define('admin', function ($user, $class, $roles) {
            if (isset($user->superuser) && $user->superuser) {
                return true;
            }

            return app('\Aimeos\Shop\Base\Support')->checkUserGroup($user, $roles);
        });
    }
}
