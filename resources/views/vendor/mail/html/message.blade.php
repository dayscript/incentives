@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <img src="{!! isset($message)?$message->embed( public_path().'/images/emails/logo.png' ):url('images/emails/logo.png') !!}" width="333" height="60" alt="{{ config('app.name') }}"><br>
            @endcomponent
    @endslot

    {{-- Body --}}
    {{ $slot }}

    {{-- Subcopy --}}
    @if (isset($subcopy))
        @slot('subcopy')
            @component('mail::subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endif

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            &copy; {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados
        @endcomponent
    @endslot
@endcomponent
