@extends('layouts.app')

@section('page-header')
    <div class="page-header page-header-default">
        <div class="page-header-content">
            <div class="page-title">
                <h4><i class="icon-users4 position-left"></i> <span class="text-semibold">{{ __('Usuarios') }}</span> - {{ __('Listado') }}
                    <small class="display-block">{{ __('Lista de usuarios creados en el sistema') }}</small>
                </h4>
            </div>
        </div>
        <div class="breadcrumb-line">
            <ul class="breadcrumb">
                <li><a href="/"><i class="icon-home2 position-left"></i> {{ __('Inicio') }}</a></li>
                <li><a>{{ __('Usuarios') }}</a></li>
                <li class="active">{{ __('Listado de usuarios') }}</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h6 class="panel-title">{{ __('Usuarios') }}</h6>
            <div class="heading-elements">
                {{ $users->links() }}
            </div>
        </div>
        <ul class="media-list media-list-linked media-list-bordered">
            @foreach($users as $user)
            <li class="media">
                <a href="/users/{{ $user->id }}/edit" class="media-link">
                    <div class="media-left">
                        <avatar image="{{ $user->avatar }}" fullname="{{ $user->name }}" :size="36"></avatar>
                    </div>
                    <div class="media-body">
                        <h6 class="media-heading">{{ $user->name }}</h6>
                        {{ $user->email }} - <small>{{ $user->position }}</small>
                    </div>
                </a>
            </li>
            @endforeach
        </ul>
        <div class="panel-footer"><a class="heading-elements-toggle"><i class="icon-more"></i></a>
            <div class="heading-elements">
                <div class="pull-right">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection