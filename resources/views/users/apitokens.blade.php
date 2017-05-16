@extends('layouts.app')

@section('page-header')
    <div class="page-header page-header-default">
        <div class="page-header-content">
            <div class="page-title">
                <h4><i class="icon-users4 position-left"></i> <span class="text-semibold">{{ __('Usuarios') }}</span> - {{ __('API Tokens') }}
                    <small class="display-block">{{ __('Administración de tokens para conexión') }}</small>
                </h4>
            </div>
        </div>
        <div class="breadcrumb-line">
            <ul class="breadcrumb">
                <li><a href="/"><i class="icon-home2 position-left"></i> {{ __('Inicio') }}</a></li>
                <li><a>{{ __('Usuarios') }}</a></li>
                <li class="active">{{ __('API Tokens') }}</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h6 class="panel-title">{{ __('API Tokens') }}</h6>
            <div class="heading-elements">
            </div>
        </div>
        <passport-personal-access-tokens></passport-personal-access-tokens>
        {{--<passport-clients></passport-clients>--}}
        {{--<passport-authorized-clients></passport-authorized-clients>--}}

        {{--eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjIyNzZmYWY1NTE1MWZlNzc4N2NiODQyMWNkMGUyMTY0NjQ2ZWNlOWZiYTQ3OWRlOGE1M2NhNDFjMzhjYzE4MWU0ODUxMTAxZDEyNGNjZDFkIn0.eyJhdWQiOiIxIiwianRpIjoiMjI3NmZhZjU1MTUxZmU3Nzg3Y2I4NDIxY2QwZTIxNjQ2NDZlY2U5ZmJhNDc5ZGU4YTUzY2E0MWMzOGNjMTgxZTQ4NTExMDFkMTI0Y2NkMWQiLCJpYXQiOjE0OTQ5NjkyMDYsIm5iZiI6MTQ5NDk2OTIwNiwiZXhwIjoxNTI2NTA1MjA2LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.N_ydHWd8K_1K1FtsLSLDveobNsK71aZGRYlYXPB8t548t64ypB0LFI-vDC9L0LFWXm-dtD8RUTmCPu-_nyPxnXs2plPBz3gfeJw4VaJWOXuwnjocOiJ-IjZFM8IdMjjHFDrZnexFj-Gzz6mtTcwinofcNeuSrlihKvyZoJmkBNGQuoTv7Epxhx2CgDZHcX_ImxOTCtK6dqm3rDxMUtVYfDeKeFiOLwe0uh1-4G2ULfvw0PfxQnsWxssECgRsW_EvJh6_jw2aHu0vlXhdb9TtkrtsjOVm285SdKlHia1eJ6tGTuV3WNPzsk-epwiUneRa7t_bTMFRrPzSLEQqyPUq_PcQCmw3JehGLU5_HW0iAPNpQhqn5qSaB9e1SVE065r_i3ZZ8oKmH2aEEqdFJ7ajJgr2APUsRz6e5HKypIuwUPL_tHhIMLZjs9IyR04Wa-5MXInJoUu7X6W5dHQp_fnguyeZ19aCBUN3LPJ5s5epRg_qMarjRYeLr8zaLGUBhP77hMkrNxgvdbGXc05wzrmmP09vgybdnMnQM3ZlVeO6X2zkfM5LxoPO_vyK9kPRTo8__R_8uo-xAd9d4Aj7k8ur7rpW2UTcNnJKTxmsEqyVDNDqreyUjQOhnHY0lEVetZI0UgLICF7YZ44zL8bfJgzgmWmp-jlIHIxT9gXl8QkPtlg--}}



    </div>
@endsection