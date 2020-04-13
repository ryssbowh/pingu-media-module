@extends('field@field')

@section('inner')
    @foreach($data['values'] as $file)
        @if(!$file)
            @continue
        @endif
        <div class="field-item">
            <a href="{{ $file->url() }}">{{ $options->label == 'custom' ? $options->custom : $file->name }}</a>
        </div>
    @endforeach
@overwrite