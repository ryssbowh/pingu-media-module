@extends('forms@field')

@section('inner')
    {{ FormFacade::file($field->getHtmlName(), $attributes->toArray()) }}
@overwrite