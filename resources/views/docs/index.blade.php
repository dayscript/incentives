@extends('layouts.docs')
@section('title')
    Documentación @parent
@endsection

@section('content')
    <div class="content">
        {!! $content !!}
    </div>
@endsection
