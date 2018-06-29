@extends('layouts.app')

@section('page-header')
    <div class="page-header page-header-default">
        <div class="page-header-content">
            <div class="page-title">
                <h4><i class="icon-add-to-list"></i> <span class="text-semibold">{{ __('Modificadores') }}</span> - {{ __('Agregar regla Modificadores') }}
                    <small class="display-block">{{ __('Agregar una nueva regla de Modificadores') }}</small>
                </h4>
            </div>
        </div>
        <div class="breadcrumb-line">
            <ul class="breadcrumb">
                <li><a href="/"><i class="icon-home2 position-left"></i> {{ __('Inicio') }}</a></li>
                <li><a>{{ __('Modificadores') }}</a></li>
                <li class="active">{{ __('Agregar regla') }}</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')

@endsection