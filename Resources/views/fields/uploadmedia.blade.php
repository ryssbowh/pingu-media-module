@extends('forms@field')

@section('inner')
    @if($media = $field->getMedia())
        <img src="{{ $media->url('icon') }}">
        {{ FormFacade::hidden($field->getHtmlName(), $media->id) }}
    @endif
    {{ FormFacade::file($field->getHtmlName(), $attributes->toArray()) }}
@overwrite