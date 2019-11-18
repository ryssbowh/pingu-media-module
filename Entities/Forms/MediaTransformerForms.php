<?php 

namespace Pingu\Media\Entities\Forms;

use Pingu\Forms\Support\BaseForms;
use Pingu\Forms\Support\Form;
use Pingu\Media\Contracts\TransformerWithOptionsContract;
use Pingu\Media\Forms\CreateTransformerOptionsForm;
use Pingu\Media\Forms\EditTransformerOptionsForm;

class MediaTransformerForms extends BaseForms
{
    public function create(array $args): Form
    {
        return new CreateTransformerOptionsForm(...$args);
    }

    public function edit(array $args): Form
    {
        return new EditTransformerOptionsForm(...$args);
    }
}