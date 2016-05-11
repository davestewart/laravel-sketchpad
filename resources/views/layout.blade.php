<!DOCTYPE html>
<html lang="en">
<head>

	<!-- meta -->
	<meta charset="UTF-8">
	<title>Sketchpad</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />

	<!-- libs -->
	<script src="/{{ $assets }}lib.min.js"></script>
	<link  href="/{{ $assets }}lib.min.css" rel="stylesheet">
	<link  href="/{{ $assets }}fonts/lato/lato.css" rel="stylesheet">

	<!-- sketchpad -->
	<meta name="route" content="{{ $route }}">
	<link  href="/{{ $assets }}sketchpad.css" rel="stylesheet">

	<!-- custom head -->
	@yield('head')

</head>
<body data-mode="home">

	@yield('content')

	<script src="/{{ $assets }}sketchpad.js"></script>

	@if ( Config::get('app.debug') ) 
	<script type="text/javascript">
		document.write('<script src="//localhost:35729/livereload.js?snipver=1" type="text/javascript"><\/script>')
	</script>
	@endif

</body>
</html>

