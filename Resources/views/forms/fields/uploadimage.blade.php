@extends('forms@field')

@section('inner')
    @if($image = $field->getImage())
        <a href="{{ $image->url() }}" target="_blank"><img src="{{ $image->urlStyle('icon') }}"></a>
    @endif
    {{ FormFacade::file($field->getHtmlName(), $attributes->toArray()) }}
@overwrite