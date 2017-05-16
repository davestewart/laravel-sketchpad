<!DOCTYPE html>
<html lang="en">
<head>

	<!-- meta -->
	<title>Sketchpad - Setup</title>
    @include('sketchpad::sketchpad-head')

	<!-- favicon -->
	<link rel="shortcut icon" href="{{ $assets }}images/favicon.ico" type="image/x-icon">
	<link rel="icon" href="{{ $assets }}images/favicon.ico" type="image/x-icon">

    <!-- setup -->
    <link  href="{{ $assets }}css/setup.css" rel="stylesheet">
	<script type="application/json" id="settings">{!! json_encode($settings, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>

</head>
<body>

	<div class="navbar navbar-default navbar-fixed-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<span class="navbar-brand">Sketchpad</span>
			</div>
		</div>
	</div>

	<div class="container-fluid" id="app">
		<div class="row">
			<setup></setup>
		</div>
	</div>

	<script src="{{ $assets }}js/setup.js"></script>

</body>
</html>

