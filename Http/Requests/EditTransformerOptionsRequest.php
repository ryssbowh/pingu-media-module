<?php

namespace Pingu\Media\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditTransformerOptionsRequest extends FormRequest
{
    public function __construct()
    {
        $this->transformer = request()->route()->parameters()['media_transformer']->instance();
        parent::__construct();
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->transformer->getValidationRules();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
