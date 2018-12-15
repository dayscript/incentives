@extends('layouts.app')

@section('page-header')
    <div class="page-header page-header-default">
        <div class="page-header-content">
            <div class="page-title">
                <h4><i class="icon-briefcase position-left"></i> <span class="text-semibold">{{ __('Exportar') }}</span> - {{ __('Listado') }}
                    <small class="display-block">{{ __('Exportar datos') }}</small>
                </h4>
            </div>
        </div>
        <div class="breadcrumb-line">
            <ul class="breadcrumb">
                <li><a href="/"><i class="icon-home2 position-left"></i> {{ __('Inicio') }}</a></li>
                <li><a>{{ __('Tools') }}</a></li>
                <li class="active">{{ __('Exportar') }}</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h6 class="panel-title">{{ __('Exportar') }}</h6>
            <div class="heading-elements">

            </div>
        </div>
        <div class="panel-body">
          <tools-export
            :models="{{ $models }}"
            :attributes="{{ $attributes }}"
            :response="{{ $response }}"
            :model="{{ $model }}"
            :attribute="{{ $attribute }}"
            >
          </tools-export>
        </div>
        <div class="panel-footer"><a class="heading-elements-toggle"><i class="icon-more"></i></a>
            <div class="heading-elements">
                <div class="pull-right">

                </div>
            </div>
        </div>
    </div>
@endsection
