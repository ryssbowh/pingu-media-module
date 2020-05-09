<?php

namespace Pingu\Media\Http\Contexts;

use Pingu\Core\Http\Contexts\UpdateContext;

class UpdateMediaTypeContext extends UpdateContext
{
    /**
     * @inheritDoc
     */
    public function getValidationRules(): array
    {
        return $model->fieldRepository()->validationRules()->except('machineName')->toArray();
    }
}