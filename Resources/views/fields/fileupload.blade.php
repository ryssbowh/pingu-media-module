@extends('forms::field')

@section('inner')
	{{ FormFacade::file($field->getName(), $field->attributes->toArray()) }}
@overwrite