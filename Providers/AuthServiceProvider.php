<?php

namespace Pingu\Media\Providers;

use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
    
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  Gate $gate
     * @return void
     */
    public function boot(Gate $gate)
    {
        //$this->registerPolicies($gate);
    }
} 