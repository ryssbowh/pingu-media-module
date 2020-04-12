<?php

namespace Pingu\Media\Entities\Validators;

class FileValidator extends MediaValidator
{
    protected function rules(bool $updating): array
    {
        return parent::rules($updating);
    }
}