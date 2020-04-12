@extends('field@field')

@section('inner')
    @foreach($data['values'] as $image)
        <div class="field-item">
            @if($options->linkTo != 'no')
                <a href="{{ $options->getLink($entity, $image) }}">
            @endif
            {!! $image->img($options->style == '_full' ? null : $options->style) !!}
            @if($options->linkTo != 'no')
                </a>
            @endif
        </div>
    @endforeach
@overwrite