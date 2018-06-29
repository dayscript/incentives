@extends('layouts.app')

@section('page-header')

    <div class="page-header page-header-default">
        <div class="page-header-content">
            <div class="page-title">
                <h4><i class="icon-users4 position-left"></i> <span class="text-semibold">{{ __('Modificadores') }}</span> - {{ __('Listado') }}
                    <small class="display-block">{{ __('Lista de Modificadores creados en el sistema') }}</small>
                </h4>
            </div>
            <div class="heading-elements">
                <a href="/modifier/create" type="button" class="btn btn-primary heading-btn">Agregar Modificadores</a>
            </div>
        </div>
        <div class="breadcrumb-line">
            <ul class="breadcrumb">
                <li><a href="/"><i class="icon-home2 position-left"></i> {{ __('Inicio') }}</a></li>
                <li><a>{{ __('Modificadores') }}</a></li>
                <li class="active">{{ __('Listado de Modificadores') }}</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h6 class="panel-title">{{ __('Modificadores') }}</h6>
            <div class="heading-elements">
                {{ $modifiers->links() }}
            </div>
        </div>
        <ul class="media-list media-list-linked media-list-bordered">
            @foreach($modifiers as $modifier)
            <li class="media">
                <a href="/modifier/{{ $modifier->id }}/edit" class="media-link">
                    <div class="media-left">
                        <avatar image="{{ $modifier->avatar }}" fullname="{{ $modifier->name }}" :size="36"></avatar>
                    </div>
                    <div class="media-body">
                        <h6 class="media-heading">{{ $modifier->name }}</h6>
                    </div>
                </a>
            </li>
            @endforeach
        </ul>
        <div class="panel-footer"><a class="heading-elements-toggle"><i class="icon-more"></i></a>
            <div class="heading-elements">
                <div class="pull-right">
                    {{ $modifiers->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection