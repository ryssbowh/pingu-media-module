<?php

namespace Pingu\Media\Traits;

use Illuminate\Support\Str;
use Pingu\Media\Contracts\TransformerContract;
use Pingu\Media\Entities\MediaTransformer;

trait Transformer
{
    /**
     * Options for this transformer
     * @var array
     */
    protected $options = [];

    /**
     * Model associated to this transformer
     * 
     * @var MediaTransformer
     */
    protected $model;

    public function __construct(?MediaTransformer $model = null)
    {
        $this->options = $model ? $model->options : [];
        $this->model = $model;
    }

    /**
     * @inheritDoc
     */
    public function getModel()
    {
        return $this->model;
    }

    public static function getSlug()
    {
        return Str::kebab(class_basename(static::class));
    }

    public function getOption($key = null)
    {
        if(is_null($key)){
            return $this->options;
        }
        return $this->options[$key] ?? null;
    }

    /**
     * Magic method to access options
     * 
     * @param  string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->options[$name] ?? null;
    }

    /**
     * Does this transformer define options 
     * 
     * @return boolean
     */
    public static function hasOptions()
    {
        return false;
    }
}