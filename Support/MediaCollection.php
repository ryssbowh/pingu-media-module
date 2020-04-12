<?php 

namespace Pingu\Media\Support;

use Illuminate\Database\Eloquent\Collection;
use Pingu\Media\Entities\Media;

class MediaCollection extends Collection
{
    public function __construct($items)
    {
        parent::__construct($items);
        $this->recastAll();
    }

    private function recastAll()
    {
        $newItems = [];
        foreach ($this->items as $model) {
            if ($model instanceof Media) {
                $instance = $model->instance;
                $instance->setRelations(['media' => $model]);
                $model = $instance;
            }
            $newItems[] = $model;
        }
        $this->items = $newItems;
    }
}