@extends('layouts.app')

@section('page-header')
    <div class="page-header page-header-default">
        <div class="page-header-content">
            <div class="page-title">
                <h4><i class="icon-add-to-list"></i> <span class="text-semibold">{{ __('Variable') }}</span> - {{ __('Agregar Variable') }}
                    <small class="display-block">{{ __('Agregar una nueva variable') }}</small>
                </h4>
            </div>
        </div>
        <div class="breadcrumb-line">
            <ul class="breadcrumb">
                <li><a href="/"><i class="icon-home2 position-left"></i> {{ __('Inicio') }}</a></li>
                <li><a>{{ __('Variable') }}</a></li>
                <li class="active">{{ __('Agregar Variable') }}</li>
            </ul>
        </div>
    </div>
@endsection
@section('content')
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">{{ __('Datos generales') }}</h5>
            <div class="heading-elements">
                <ul class="icons-list">
                    <li><a data-action="collapse"></a></li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            <edit-var :rule_id="0"></edit-var>
        </div>
    </div>
@endsection
