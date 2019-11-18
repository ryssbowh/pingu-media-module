<?php

namespace Pingu\Media\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EditMediaForm
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if ($event->form->getName() == 'edit-media') {
            $event->form->getElement('disk')->attribute('disabled', true);
            $event->form->getElement('size')->attribute('disabled', true);
            $event->form->getElement('filename')->attribute('disabled', true);
        }
    }
}
