<!DOCTYPE html>
<html lang="en">
<head>

    <!-- meta -->
    <title>Sketchpad</title>
    <meta name="route" content="{{ $route }}">
    @include('sketchpad::head')

    <!-- user -->
    <link  href="/{{ $assets }}user/user.css" rel="stylesheet">
    <script src="/{{ $assets }}user/user.js"></script>

    <!-- custom head -->
    @yield('head')

</head>
<body data-mode="home">

    <div id="app">
        <app></app>
    </div>

	<script id="data" type="text/plain">{!! json_encode($data, JSON_UNESCAPED_SLASHES) !!}</script>
    <script src="/{{ $assets }}js/app.js"></script>

    @if ( Config::get('app.debug') )
    <script src="//localhost:35729/livereload.js?snipver=1" type="text/javascript"></script>
    @endif
	<!--
	<a data-toggle="modal" href="http://fiddle.jshell.net/bHmRB/51/show/" data-target="#modal">Click me !</a>
	-->
</body>
</html>


