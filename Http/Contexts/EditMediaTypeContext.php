<?php

namespace Pingu\Media\Http\Contexts;

use Illuminate\Support\Collection;
use Pingu\Core\Http\Contexts\EditContext;

class EditMediaTypeContext extends EditContext
{
    /**
     * @inheritDoc
     */
    public function getFields(): Collection
    {
        $fields = $this->object->fieldRepository()->all();
        $fields->get('machineName')->option('disabled', true);
        return $fields;
    }
}