<!DOCTYPE html>
<html lang="en">
<head>

    <!-- meta -->
    <meta charset="UTF-8">
    <title>Sketchpad</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- fonts -->
    <link  href="/{{ $assets }}fonts/lato/lato.css" rel="stylesheet">
    <link  href="/{{ $assets }}fonts/font-awesome/font-awesome.min.css" rel="stylesheet">

    <!-- libs -->
    <script src="/{{ $assets }}js/lib.js"></script>
    <link  href="/{{ $assets }}css/lib.css" rel="stylesheet">

    <!-- sketchpad -->
    <meta name="route" content="{{ $route }}">
    <link  href="/{{ $assets }}css/app.css" rel="stylesheet">
    <link  href="/{{ $assets }}css/components.css" rel="stylesheet">

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


