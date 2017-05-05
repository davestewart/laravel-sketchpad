<!DOCTYPE html>
<html lang="en">
<head>

    <!-- meta -->
    <title>Sketchpad</title>
    <meta name="route" content="{{ $route }}">
    @include('sketchpad::head')

	@if ($livereload->host)<!-- file watching -->
    <script src="http://{{ $livereload->host }}:35729/livereload.js"></script>
	@endif

    <!-- user -->
</head>
<body>

    <div id="app"></div>

    <!-- data -->
	<script id="admin" type="text/plain">{!! json_encode($admin, JSON_UNESCAPED_SLASHES) !!}</script>
	<script id="settings" type="text/plain">{!! json_encode($settings, JSON_UNESCAPED_SLASHES) !!}</script>
	<script id="data" type="text/plain">{!! json_encode($data, JSON_UNESCAPED_SLASHES) !!}</script>
	<script id="page-home" type="text/template">{!! $home !!}</script>
	<script id="page-help" type="text/template">{!! $help !!}</script>

    <!-- app -->
    <script src="{{ $assets }}js/app.js"></script>

</body>
</html>


