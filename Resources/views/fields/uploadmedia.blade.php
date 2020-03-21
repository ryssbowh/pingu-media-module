@extends('forms@field')

@section('inner')
    @if($media = $field->getMedia())
        <img src="{{ $media->url('icon') }}">
    @endif
    {{ FormFacade::file($field->getHtmlName(), $attributes->toArray()) }}
@overwrite