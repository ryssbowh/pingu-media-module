<?php

namespace Pingu\Media\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EditMediaForm
{
    /**
     * Handle the event.
     *
     * @param  object $event
     * @return void
     */
    public function handle($event)
    {
        if ($event->form->getName() == 'edit-model-media') {
            $event->form->getElement('disk')->option('disabled', true);
            $event->form->getElement('size')->option('disabled', true);
            $event->form->getElement('filename')->option('disabled', true);
        }
        if ($event->form->getName() == 'edit-model-mediatype') {
            $event->form->getElement('machineName')->option('disabled', true);
        }
    }
}
