<!DOCTYPE html>
<html lang="en">
<head>

	<!-- meta -->
	<meta charset="UTF-8">
	<title>Sketchpad</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />

	<!-- libs -->
	<script src="/{{ $assets }}lib/vue.js"></script>
	<script src="/{{ $assets }}lib/jquery-1.12.3.min.js"></script>
	<script src="/{{ $assets }}lib/jquery.sticky.js"></script>
	<script src="/{{ $assets }}lib/history.js"></script>

	<!-- json view -->
	<link  href="/{{ $assets }}lib/jsonview/jquery.jsonview.min.css" rel="stylesheet">
	<script src="/{{ $assets }}lib/jsonview/jquery.jsonview.min.js"></script>

	<!-- bootstrap -->
	<link  href="/{{ $assets }}fonts/lato/lato.css" rel="stylesheet">
	<link  href="/{{ $assets }}lib/bootstrap/bootstrap.min.css" rel="stylesheet">
	<script src="/{{ $assets }}lib/bootstrap/bootstrap.min.js"></script>

	<!-- sketchpad -->
	<link  href="/{{ $assets }}sketchpad.css" rel="stylesheet">

	<!-- variables -->
	<meta name="route" content="{{ $route }}">

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

