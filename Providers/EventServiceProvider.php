<?php

namespace Pingu\Media\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Pingu\Forms\Events\FormBuilt;
use Pingu\Media\Listeners\EditMediaForm;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        FormBuilt::class => [
            EditMediaForm::class
        ]
    ];
}