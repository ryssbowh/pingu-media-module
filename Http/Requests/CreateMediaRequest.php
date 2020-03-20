<?php

namespace Pingu\Media\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateMediaRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file' => 'required|file|defined_extension|max:'.config('media.maxFileSize')
        ];
    }
}
