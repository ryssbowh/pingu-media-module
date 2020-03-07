<?php
namespace Pingu\Media\Forms;

use Pingu\Forms\Support\Fields\Hidden;
use Pingu\Forms\Support\Fields\Select;
use Pingu\Forms\Support\Fields\Submit;
use Pingu\Forms\Support\Fields\TextInput;
use Pingu\Forms\Support\Form;
use Pingu\Media\Entities\ImageStyle;
use Pingu\Media\Entities\MediaTransformer;

class AddTransformerForm extends Form
{
    /**
     * Bring variables in your form through the constructor :
     */
    public function __construct(ImageStyle $style)
    {
        $this->style = $style;
        parent::__construct();
    }

    /**
     * Fields definitions for this form, classes used here
     * must extend Pingu\Forms\Support\Field
     * 
     * @return array
     */
    public function elements(): array
    {
        $transformers = \Media::getTransformers();

        $items = [];
        foreach ($transformers as $transformer) {
            $items[$transformer::getSlug()] = $transformer::getName();
        }

        return [
            new Select(
                'transformer',
                [
                    'items' => $items,
                    'showLabel' => false
                ]
            ),
            new Submit('submit')
        ];
    }

    /**
     * Method for this form, POST GET DELETE PATCH and PUT are valid
     * 
     * @return string
     */
    public function method(): string
    {
        return 'GET';
    }

    /**
     * Url for this form, valid values are
     * ['url' => '/foo.bar']
     * ['route' => 'login']
     * ['action' => 'MyController@action']
     * 
     * @return array
     * @see    https://github.com/LaravelCollective/docs/blob/5.6/html.md
     */
    public function action(): array
    {
        return ['url' => MediaTransformer::uris()->make('create', $this->style, adminPrefix())];
    }

    /**
     * Name for this form, ideally it would be application unique, 
     * best to prefix it with the name of the module it's for.
     * only alphanumeric and hyphens
     * 
     * @return string
     */
    public function name(): string
    {
        return 'media-transformer-create';
    }
}