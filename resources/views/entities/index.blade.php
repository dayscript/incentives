@extends('layouts.app')

@section('page-header')
    <div class="page-header page-header-default">
        <div class="page-header-content">
            <div class="page-title">
                <h4><i class="icon-briefcase position-left"></i> <span class="text-semibold">{{ __('Entidades') }}</span> - {{ __('Listado') }}
                    <small class="display-block">{{ __('Entidades creadas en el sistema') }}</small>
                </h4>
            </div>
        </div>
        <div class="breadcrumb-line">
            <ul class="breadcrumb">
                <li><a href="/"><i class="icon-home2 position-left"></i> {{ __('Inicio') }}</a></li>
                <li><a>{{ __('Entidades') }}</a></li>
                <li class="active">{{ __('Listado') }}</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h6 class="panel-title">{{ __('Entidades') }}</h6>
            <div class="heading-elements">
                {{ $entities->links() }}
            </div>
        </div>
        <ul class="media-list media-list-linked media-list-bordered">
            @foreach($entities as $entity)
            <li class="media">
                <a href="#" class="media-link">
                    <div class="media-left">
                        ID: {{ $entity->id }}
                    </div>
                    <div class="media-body">
                        <h6 class="media-heading">{{ $entity->identification }} {{ $entity->name }}</h6>
                        {{ $entity->created_at }}
                    </div>
                    <div class="media-right">
                        {{ number_format($entity->totalpoints(),2,',','.') }}
                    </div>
                </a>
            </li>
            @endforeach
        </ul>
        <div class="panel-footer"><a class="heading-elements-toggle"><i class="icon-more"></i></a>
            <div class="heading-elements">
                <div class="pull-right">
                    {{ $entities->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection