<?php

namespace Pingu\Media\Entities\Fields;

use Pingu\Field\BaseFields\Integer;
use Pingu\Field\Support\FieldRepository\BaseFieldRepository;

class MediaTransformerFields extends BaseFieldRepository
{
    protected function fields(): array
    {
        return [
            new Integer('weight')
        ];
    }
}