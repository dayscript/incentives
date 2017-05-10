@extends('layouts.app')

@section('page-header')
    <div class="page-header page-header-default">
        <div class="page-header-content">
            <div class="page-title">
                <h4><i class="icon-briefcase position-left"></i> <span class="text-semibold">{{ __('Clientes') }}</span> - {{ __('Listado') }}
                    <small class="display-block">{{ __('Lista de clientes creados en el sistema') }}</small>
                </h4>
            </div>
        </div>
        <div class="breadcrumb-line">
            <ul class="breadcrumb">
                <li><a href="/"><i class="icon-home2 position-left"></i> {{ __('Inicio') }}</a></li>
                <li><a>{{ __('Clientes') }}</a></li>
                <li class="active">{{ __('Listado de clientes') }}</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h6 class="panel-title">{{ __('Clientes') }}</h6>
            <div class="heading-elements">
                {{ $clients->links() }}
            </div>
        </div>
        <ul class="media-list media-list-linked media-list-bordered">
            @foreach($clients as $client)
            <li class="media">
                <a href="/clients/{{ $client->id }}/edit" class="media-link">
                    <div class="media-left">
                        <avatar image="{{ $client->image }}" fullname="{{ $client->name }}" :size="36"></avatar>
                    </div>
                    <div class="media-body">
                        <h6 class="media-heading">{{ $client->name }}</h6>
                        {{ $client->created_at }}
                    </div>
                </a>
            </li>
            @endforeach
        </ul>
        <div class="panel-footer"><a class="heading-elements-toggle"><i class="icon-more"></i></a>
            <div class="heading-elements">
                <div class="pull-right">
                    {{ $clients->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection