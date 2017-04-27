@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-body login-form">
                <div class="text-center">
                    <div class="icon-object border-warning text-warning"><i class="icon-spinner11"></i></div>
                    <h5 class="content-group">{{ __('Recuperar contraseña') }}
                        <small class="display-block">{{ __('Enviremos instrucciones a tu correo electrónico') }}</small>
                    </h5>
                </div>
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                <form class="form-horizontal" role="form" method="POST" action="{{ route('password.email') }}">
                    {{ csrf_field() }}
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} has-feedback">
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}"
                               placeholder="{{ __('Tu correo electrónico') }}" required>
                        <div class="form-control-feedback">
                            <i class="icon-mail5 text-muted"></i>
                        </div>
                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn bg-blue btn-block">{{ __('Enviar correo para reasignar contraseña') }} <i
                                class="icon-arrow-right14 position-right"></i></button>
                    </div>
                    <div class="text-center">
                        <a href="{{ route('login') }}">{{ __('Volver a Ingresar') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
