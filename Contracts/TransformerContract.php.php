<?php 
namespace Pingu\Media\Contracts;

use Illuminate\Http\Request;
use Pingu\Media\Entities\ImageStyle;
use Pingu\Media\Entities\MediaTransformer;

interface TransformerContract
{
    /**
     * get the url slug gfor this transformer
     * 
     * @return string
     */
    public static function getSlug();

    /**
     * get this transformer's name
     * 
     * @return string
     */
    public static function getName();    

    /**
     * Returns a friendly description of this transformation
     * 
     * @return string
     */
    public function getDescription();

    /**
     * process the file
     *
     * @param  string $file
     * @return bool
     */
    public function process(string $file);

    /**
     * Does this transformer define options
     * 
     * @return bool
     */
    public static function hasOptions();

    /**
     * Get model associated with this transformer
     * 
     * @return MediaTransformer
     */
    public function getModel();
}