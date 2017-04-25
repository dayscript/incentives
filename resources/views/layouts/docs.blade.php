<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    @section('meta')
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta property="og:site_name" content="Optafeeds"/>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @show
    <title>@section('title') :: {{ config('app.name', '') }} @show</title>
    <link rel="shortcut icon" href="/favicon.ico"/>
    <script src="https://use.fontawesome.com/4505d862ad.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.4.0/css/bulma.min.css" type="text/css">
</head>
<body>
<section class="hero is-medium is-primary is-bold">
    <div class="hero-body">
        <div class="container">
            <h1 class="title">
                Documentación {{ config('app.name', '') }}
            </h1>
            <h2 class="subtitle">
                Actualizado: {{ date ("F d Y H:i:s", mostRecentModifiedFileTime(resource_path('views/docs/'), true)) }}
            </h2>
        </div>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="columns">
            <div class="column is-one-quarter">
                @include('docs.menu')
            </div>
            <div class="column">
                @yield('content')
            </div>
        </div>
    </div>
</section>

<footer class="footer">
    <div class="container">
        <div class="content has-text-centered">
            <p>
                <strong>{{ config('app.name', '') }}</strong> by <a href="http://www.dayscript.com">Dayscript</a>.
            </p>
            <p>
                <a class="icon" href="https://github.com/dayscript/incentives">
                    <i class="fa fa-github"></i>
                </a>
            </p>
        </div>
    </div>
</footer>

</body>
</html>