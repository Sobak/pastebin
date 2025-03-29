<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ page_title($title ?? '') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ mix('assets/css/app.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:300,500&subset=latin-ext">
</head>
<body>
    @yield('content')

    @if (session('alert'))
    <div class="alert-box alert-{{session('alert-type', 'success')}}">
        <p>{{ session('alert') }}</p>
    </div>
    @endif

    <footer id="footer">
        Created by <a href="https://sobak.pl">Sobak</a>
        <span class="mobile-break separator">/</span>
        Highlighted with <a href="https://keylighter.kadet.net">KeyLighter</a> {{$keylighterVersion}}
        @yield('keylighter-append-info')
        <span class="mobile-break separator">/</span>
        Check the <a href="https://github.com/Sobak/pastebin">source code</a>
    </footer>

    <script src="{{ mix('assets/js/app.js') }}"></script>
    @yield('footer-extra-scripts')
</body>
</html>
