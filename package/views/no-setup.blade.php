<!DOCTYPE html>
<html lang="en">
<head>

	<!-- meta -->
	<title>Sketchpad - Setup</title>
    @include('sketchpad::head')

    <!-- setup -->
    <link  href="{{ $assets }}css/setup.css" rel="stylesheet">

</head>
<body>

	<div class="navbar navbar-default navbar-fixed-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="{{ $route }}">Sketchpad</a>
			</div>
		</div>
	</div>

	<div class="container-fluid" id="app">
		<div id="setup">

			<header>
				<h1>Setup</h1>
			</header>

			<section>
				<article>
					<h2 class="text-danger">Setup is disabled</h2>
					<p>If you think this is a mistake, contact your System Administrator.</p>
					<p>If you are the System Administrator see the <a href="https://github.com/davestewart/laravel-sketchpad/wiki/Admin" target="_blank">wiki</a> on how to enable setup.</p>
				</article>
			</section>

		</div>
	</div>

</body>
</html>

