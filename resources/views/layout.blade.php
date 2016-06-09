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

	<!-- fonts -->
	<link  href="/{{ $assets }}fonts/lato/lato.css" rel="stylesheet">
	<link  href="/{{ $assets }}fonts/font-awesome/font-awesome.min.css" rel="stylesheet">

	<!-- sketchpad -->
	<meta name="route" content="{{ $route }}">
	<link  href="/{{ $assets }}app.css" rel="stylesheet">

	<!-- user -->
	<link  href="/{{ $assets }}user.css" rel="stylesheet">
	<script src="/{{ $assets }}user.js"></script>

	<!-- custom head -->
	@yield('head')

</head>
<body data-mode="home">

	@yield('content')

	@if ( Config::get('app.debug') )
	<script src="//localhost:35729/livereload.js?snipver=1" type="text/javascript"></script>
	@endif

	<script src="/{{ $assets }}app.js"></script>

</body>
</html>

