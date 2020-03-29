<?php 

namespace Pingu\Media\Entities\Forms;

use Pingu\Entity\Support\Forms\BaseEntityForms;
use Pingu\Forms\Support\Form;
use Pingu\Media\Entities\Media;
use Pingu\Media\Forms\CreateMedia;

class MediaForms extends BaseEntityForms
{
    public function create(array $args): Form
    {
        $url = Media::uris()->make('store', [], adminPrefix());
        return new CreateMedia(['url' => $url]);
    }
}