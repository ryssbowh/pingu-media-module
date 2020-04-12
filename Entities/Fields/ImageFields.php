<?php

namespace Pingu\Media\Entities\Fields;

use Pingu\Field\BaseFields\Model;
use Pingu\Field\BaseFields\Text;
use Pingu\Field\Support\FieldRepository\BaseFieldRepository;
use Pingu\Media\Entities\Media;
use Pingu\Media\Entities\MediaType;

class ImageFields extends BaseFieldRepository
{
    protected function fields(): array
    {
        return (new Media)->fields()->get()->all() + [
            new Text('alt')
        ];
    }
}