@extends('layouts.app')

@section('page-header')
    <div class="page-header page-header-default">
        <div class="page-header-content">
            <div class="page-title">
                <h4><i class="icon-add-to-list"></i> <span class="text-semibold">{{ __('Usuarios') }}</span> - {{ __('Roles') }}
                    <small class="display-block">{{ __('Lista de roles creados en el sistema') }}</small>
                </h4>
            </div>
            <div class="heading-elements">
                <a href="/roles/create" type="button" class="btn btn-primary heading-btn">Agregar rol</a>
            </div>
        </div>
        <div class="breadcrumb-line">
            <ul class="breadcrumb">
                <li><a href="/"><i class="icon-home2 position-left"></i> {{ __('Inicio') }}</a></li>
                <li><a>{{ __('Usuarios') }}</a></li>
                <li class="active">{{ __('Roles') }}</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h6 class="panel-title">{{ __('Roles') }}</h6>
            <div class="heading-elements">
                {{ $role->links() }}
            </div>
        </div>
        <ul class="media-list media-list-linked media-list-bordered">
            @foreach($role as $item)
            <li class="media">
                <a href="/roles/{{ $item->id }}/edit" class="media-link">
                    <div class="media-left">
                        <!--avatar image="{{ $item->avatar }}" fullname="{{ $item->name }}" :size="36"></avatar-->
                    </div>
                    <div class="media-body">
                        <h6 class="media-heading">{{ $item->name }} - {{ $item->rol->name }}</h6>
                        <!--{{ $item->email }} - <small>{{ $item->position }}</small-->
                    </div>
                </a>
            </li>
            @endforeach
        </ul>
        <div class="panel-footer"><a class="heading-elements-toggle"><i class="icon-more"></i></a>
            <div class="heading-elements">
                <div class="pull-right">
                    {{ $role->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection