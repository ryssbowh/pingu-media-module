@extends('forms@field')

@section('inner')
    @if($file = $field->getFile())
        <a href="{{ $file->url() }}" target="_blank">{{ $file->name }}</a>
    @endif
    {{ FormFacade::file($field->getHtmlName(), $attributes->toArray()) }}
@overwrite