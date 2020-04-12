<?php

namespace Pingu\Media\Entities\Validators;

class ImageValidator extends MediaValidator
{
    protected function rules(bool $updating): array
    {
        return parent::rules($updating);
    }
}