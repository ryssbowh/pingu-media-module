<?php
namespace Pingu\Media\Forms;

use Pingu\Forms\Support\Fields\Hidden;
use Pingu\Forms\Support\Fields\Link;
use Pingu\Forms\Support\Fields\Submit;
use Pingu\Forms\Support\Form;
use Pingu\Media\Contracts\TransformerWithOptionsContract;
use Pingu\Media\Entities\ImageStyle;
use Pingu\Media\Entities\MediaTransformer;

class CreateTransformerOptionsForm extends Form
{
    /**
     * Bring variables in your form through the constructor :
     */
    public function __construct(array $action, TransformerWithOptionsContract $transformer)
    {
        $this->transformer = $transformer;
        $this->action = $action;
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
        $fields = $this->transformer->getOptionsFields();
        $fields[] = new Submit();
        
        $fields[] = new Hidden(
            '_transformer',
            [
                'default' => $this->transformer::getSlug()
            ]
        );
        return $fields;
    }

    /**
     * Method for this form, POST GET DELETE PATCH and PUT are valid
     * 
     * @return string
     */
    public function method(): string
    {
        return 'POST';
    }

    /**
     * Url for this form, valid values are
     * ['url' => '/foo.bar']
     * ['route' => 'login']
     * ['action' => 'MyController@action']
     * 
     * @return array
     * @see https://github.com/LaravelCollective/docs/blob/5.6/html.md
     */
    public function action(): array
    {
        return $this->action;
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
        return 'media-add-transformer';
    }

    /**
     * Various options that you can access in your templates/events
     *
     * @return array
     */
    public function options(): array
    {
        return [
            'title' => 'Add '.$this->transformer->getName()
        ];
    }
}