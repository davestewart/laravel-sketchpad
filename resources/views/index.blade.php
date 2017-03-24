<!DOCTYPE html>
<html lang="en">
<head>

    <!-- meta -->
    <title>Sketchpad</title>
    <meta name="route" content="{{ $route }}">
    @include('sketchpad::head')

	@if ($watcher)<!-- file watching -->
	<script src="{{ $watcher }}"></script>
	@endif

    <!-- user -->
</head>
<body>

    <div id="app"></div>

	<script id="settings" type="text/plain">{!! json_encode($settings, JSON_UNESCAPED_SLASHES) !!}</script>
	<script id="data" type="text/plain">{!! json_encode($data, JSON_UNESCAPED_SLASHES) !!}</script>
    <script src="{{ $assets }}js/app.js"></script>

	<!--
	<a data-toggle="modal" href="http://fiddle.jshell.net/bHmRB/51/show/" data-target="#modal">Click me !</a>
	-->
</body>
</html>


