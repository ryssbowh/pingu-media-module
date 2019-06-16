<?php

namespace Pingu\Media\Http\Controllers;

use Illuminate\Http\Request;
use Pingu\Core\Contracts\Controllers\CreatesModelContract;
use Pingu\Core\Http\Controllers\BaseController;
use Pingu\Core\Traits\Controllers\CreatesModel;
use Pingu\Jsgrid\Contracts\Controllers\JsGridContract;
use Pingu\Jsgrid\Traits\Controllers\JsGrid;
use Pingu\Media\Entities\Media;

class MediaController extends BaseController implements JsGridContract, CreatesModelContract
{
    use JsGrid, CreatesModel;

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

    public function store()
    {
        $model = new Media;
        try{
            $validated = $this->validateStoreRequest($model);
        }
        catch(\Exception $e){
            return $this->onStoreFailure($model, $e);
        }

        \Notify::success("Media has been saved");
        return redirect()->route('media.admin.media');
    }
}
