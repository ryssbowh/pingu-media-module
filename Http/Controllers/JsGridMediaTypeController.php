<?php

namespace Pingu\Media\Http\Controllers;

use Illuminate\Http\Request;
use Pingu\Core\Entities\BaseModel;
use Pingu\Forms\Support\Form;
use Pingu\Jsgrid\Http\Controllers\JsGridModelController;
use Pingu\Media\Entities\MediaType;

class JsGridMediaTypeController extends JsGridModelController
{
    /**
     * @inheritDoc
     */
    public function getModel(): string
    {
        return MediaType::class;
    }

    /**
     * @inheritDoc
     */
    protected function canClick()
    {
        return \Auth::user()->can('edit media types');
    }

    /**
     * @inheritDoc
     */
    protected function canDelete()
    {
        return \Auth::user()->can('delete media types');
    }

    /**
     * @inheritDoc
     */
    protected function canEdit()
    {
        return \Auth::user()->can('edit media types');
    }

    /**
     * @inheritDoc
     */
    public function index(Request $request)
    {
        $options['jsgrid'] = $this->buildJsGridView($request);
        $options['title'] = 'Media Types';
        $options['canSeeAddLink'] = \Auth::user()->can('add media types');
        $options['addLink'] = MediaType::getAdminUri('create', true);
        
        return view('pages.listModel-jsGrid', $options);
    }
}
