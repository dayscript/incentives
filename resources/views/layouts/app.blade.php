<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
<div class="layout-boxed" id="app">

@include('layouts.partials.navbar')
<!-- Page container -->
    <div class="page-container">
        <!-- Page content -->
        <div class="page-content">
            @if(Auth::check())
                @include('layouts.partials.sidebar')
            @endif
            <div class="content-wrapper">
                @yield('page-header')
                <div class="content">
                    <div class="page-loader" :class="{'active':active}">
                        <div class="theme_xbox_xs"><div class="pace_progress" data-progress-text="60%" data-progress="60"></div><div class="pace_activity"></div></div>
                    </div>
                    @yield('content')
                    <div class="footer text-muted">
                        &copy; {{ date('Y') }}. <a href="/">{{ config('app.name', 'Laravel') }}</a> by <a href="http://dayscript.com"
                                                                                                          target="_blank">Dayscript</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /page content -->
    </div>
</div>
<!-- /page container -->

<!-- Scripts -->
<script type="text/javascript" src="{{ asset('limitless_1_6/layout_1/LTR/default/assets/js/plugins/loaders/pace.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('limitless_1_6/layout_1/LTR/default/assets/js/plugins/notifications/pnotify.min.js') }}"></script>
<script type="text/javascript" src="/limitless_1_6/layout_1/LTR/default/assets/js/plugins/forms/selects/select2.min.js"></script>
<script src="{{ mix('/js/app.js') }}"></script>
@yield('scripts')
</body>
</html>
