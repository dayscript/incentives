@extends('layouts.app')

@section('page-header')
    <div class="page-header page-header-default">
        <div class="page-header-content">
            <div class="page-title">
                <h4><i class="icon-add-to-list"></i> <span class="text-semibold">{{ __('Acumulación') }}</span> - {{ __('Metas') }}
                    <small class="display-block">{{ __('Lista de metas creadas en el sistema') }}</small>
                </h4>
            </div>
            <div class="heading-elements">
                <a href="/goals/create" type="button" class="btn btn-primary heading-btn">Agregar meta</a>
            </div>
        </div>
        <div class="breadcrumb-line">
            <ul class="breadcrumb">
                <li><a href="/"><i class="icon-home2 position-left"></i> {{ __('Inicio') }}</a></li>
                <li><a>{{ __('Acumulación') }}</a></li>
                <li class="active">{{ __('Metas') }}</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h6 class="panel-title">{{ __('Metas') }}</h6>
            <div class="heading-elements">
                {{ $goals->links() }}
            </div>
        </div>
        <ul class="media-list media-list-linked media-list-bordered">
            @foreach($goals as $goal)
            <li class="media">
                <a href="/goals/{{ $goal->id }}/edit" class="media-link">
                    <div class="media-left">
                        ID: {{ $goal->id }}
                        @if($client = $goal->client)
                            <avatar image="{{ $client->image }}" fullname="{{ $client->name }}" :size="36"></avatar>
                        @endif
                    </div>
                    <div class="media-body">
                        <h6 class="media-heading">{{ $goal->name }} - {{$goal->rol->name}}</h6>
                        {{ $goal->description }}
                    </div>
                    <div class="media-right">
                        Peso: {{ $goal->weight }}%
                        <br>
{{--                        {{ number_format($rule->points,2,',','.') }}--}}
                    </div>
                </a>
            </li>
            @endforeach
        </ul>
        <div class="panel-footer"><a class="heading-elements-toggle"><i class="icon-more"></i></a>
            <div class="heading-elements">
                <div class="pull-right">
                    {{ $goals->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection