@extends('layouts.app')

@section('page-header')
    <div class="page-header page-header-default">
        <div class="page-header-content">
            <div class="page-title">
                <h4><i class="icon-add-to-list"></i> <span class="text-semibold">{{ __('Acumulación') }}</span> - {{ __('Listado de reglas') }}
                    <small class="display-block">{{ __('Lista de reglas creadas en el sistema') }}</small>
                </h4>
            </div>
        </div>
        <div class="breadcrumb-line">
            <ul class="breadcrumb">
                <li><a href="/"><i class="icon-home2 position-left"></i> {{ __('Inicio') }}</a></li>
                <li><a>{{ __('Acumulación') }}</a></li>
                <li class="active">{{ __('Listado de reglas') }}</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h6 class="panel-title">{{ __('Reglas de acumulación') }}</h6>
            <div class="heading-elements">
                {{ $rules->links() }}
            </div>
        </div>
        <ul class="media-list media-list-linked media-list-bordered">
            @foreach($rules as $rule)
            <li class="media">
                <a href="/rules/{{ $rule->id }}/edit" class="media-link">
                    <div class="media-left">
                        ID: {{ $rule->id }}
                    </div>
                    <div class="media-body">
                        <h6 class="media-heading">{{ $rule->name }}</h6>
                        {{ $rule->description }}
                    </div>
                    <div class="media-right"><br>
                        {{ number_format($rule->points,2,',','.') }}
                    </div>
                </a>
            </li>
            @endforeach
        </ul>
        <div class="panel-footer"><a class="heading-elements-toggle"><i class="icon-more"></i></a>
            <div class="heading-elements">
                <div class="pull-right">
                    {{ $rules->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection