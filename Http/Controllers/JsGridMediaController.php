<?php

namespace Pingu\Media\Http\Controllers;

use Illuminate\Http\Request;
use Pingu\Core\Entities\BaseModel;
use Pingu\Forms\Support\Form;
use Pingu\Jsgrid\Http\Controllers\JsGridModelController;
use Pingu\Media\Entities\Media;

class JsGridMediaController extends JsGridModelController
{
    /**
     * @inheritDoc
     */
    public function getModel(): string
    {
        return Media::class;
    }

    /**
     * @inheritDoc
     */
    protected function canClick()
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    protected function canDelete()
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    protected function canEdit()
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function index(Request $request)
    {
        $options['jsgrid'] = $this->buildJsGridView($request);
        $options['title'] = 'Media';
        $options['canSeeAddLink'] = true;
        $options['addLink'] = Media::getAdminUri('create', true);
        
        return view('pages.listModel-jsGrid', $options);
    }
}
