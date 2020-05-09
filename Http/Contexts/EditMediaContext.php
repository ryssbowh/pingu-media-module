<?php

namespace Pingu\Media\Http\Contexts;

use Illuminate\Support\Collection;
use Pingu\Core\Http\Contexts\EditContext;

class EditMediaContext extends EditContext
{
    /**
     * @inheritDoc
     */
    public function getFields(): Collection
    {
        $fields = $this->object->fieldRepository()->all();
        $fields->get('filename')->option('disabled', true);
        $fields->get('size')->option('disabled', true);
        $fields->get('disk')->option('disabled', true);
        return $fields;
    }
}