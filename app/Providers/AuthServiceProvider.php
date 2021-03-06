<?php

namespace App\Providers;

use App\Policies\ThreadPolicy;
use App\Models\Thread;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
         'App\Models\Thread' => 'App\Policies\ThreadPolicy',
         'App\Models\Reply' => 'App\Policies\ReplyPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // if we are sign in as Admin we can do whatever we want on the page.
        Gate::before(function ($user) {
            if ($user->name === 'Admin') return true;
        });
    }
}
