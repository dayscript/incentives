@extends('layouts.app')

@section('page-header')
    <div class="page-header page-header-default">
        <div class="page-header-content">
            <div class="page-title">
                <h4><i class="icon-add-to-list"></i> <span class="text-semibold">{{ __('Acumulación') }}</span> - {{ __('Vars') }}
                    <small class="display-block">{{ __('Lista de Vars creadas en el sistema') }}</small>
                </h4>
            </div>
            <div class="heading-elements">
                <a href="/vars/create" type="button" class="btn btn-primary heading-btn">Agregar Var</a>
            </div>
        </div>
        <div class="breadcrumb-line">
            <ul class="breadcrumb">
                <li><a href="/"><i class="icon-home2 position-left"></i> {{ __('Inicio') }}</a></li>
                <li><a>{{ __('Acumulación') }}</a></li>
                <li class="active">{{ __('Vars') }}</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h6 class="panel-title">{{ __('Vars') }}</h6>
            <div class="heading-elements">
                {{ $vars->links() }}
            </div>
        </div>
        <ul class="media-list media-list-linked media-list-bordered">
            @foreach($vars as $var)
            <li class="media">
                <a href="/vars/{{ $var->id }}/edit" class="media-link">
                    <div class="media-left">
                        ID: {{ $var->id }}
                        @if($client = $var->client)
                            <avatar image="{{ $client->image }}" fullname="{{ $client->name }}" :size="36"></avatar>
                        @endif
                    </div>
                    <div class="media-body">
                        <h6 class="media-heading">{{ $var->name }}</h6>
                        {{ $var->description }}
                    </div>
                    <div class="media-right"><br>
                        {{ number_format($var->points,2,',','.') }}
                    </div>
                </a>
            </li>
            @endforeach
        </ul>
        <div class="panel-footer"><a class="heading-elements-toggle"><i class="icon-more"></i></a>
            <div class="heading-elements">
                <div class="pull-right">
                    {{ $vars->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
