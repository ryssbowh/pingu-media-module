<?php

namespace Pingu\Media\Http\Controllers;

use Pingu\Entity\Http\Controllers\AdminEntityController;
use Pingu\Entity\Support\Entity;
use Pingu\Forms\Support\Form;

class MediaTypeAdminController extends AdminEntityController
{
    /**
     * @inheritDoc
     */
    protected function afterEditFormCreated(Form $form, Entity $entity)
    {
        $form->getElement('machineName')->option('disabled', true);
    }
}
