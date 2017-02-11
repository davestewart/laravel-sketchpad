<!DOCTYPE html>
<html lang="en">
<head>

	<!-- meta -->
	<title>Sketchpad - Setup</title>
    @include('sketchpad::head')

    <!-- setup -->
    <link  href="/{{ $assets }}css/setup.css" rel="stylesheet">

	<script type="application/json" id="settings">{!! json_encode($settings, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>

</head>
<body>

	<div class="navbar navbar-default navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<span class="navbar-brand">Sketchpad</span>
			</div>
		</div>
	</div>

	<div class="container" id="app">
		<div class="row">
			<setup></setup>
		</div>
	</div>

	<script src="/{{ $assets }}js/setup.js"></script>

</body>
</html>

