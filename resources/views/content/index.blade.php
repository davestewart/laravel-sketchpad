@extends('doodle::layout')

@section('content')

	<h1>Doodle</h1>

	<section id="nav">
		@include('doodle::nav.folder')
	</section>

	<div>

		<section id="info">
			<h2>Title</h2>
			<p>Comment</p>
		</section>

		<section id="result">
			<iframe id="frame" name="frame" src="" frameborder="0"></iframe>
		</section>

	</div>

	<script>
		var $nav    = $('#nav');
		var $result = $('#result');
		var $frame  = $('#frame');

		$nav.on('click', 'a', function(event)
		{
			// prevent default
			event.preventDefault();

			// debug
			console.log(this);

			// variables
			var $link   = $(this);
			var url     = $link.attr('href');

			// route
			router.navigate(url);

			// do something with content
			if($link.is('.folder'))
			{
				$.get(url, function(html){
					$nav.html(html);
				});
			}
			else if($link.is('.method'))
			{
				$.get(url, function(html){
					$result.html(html);
				});
			}
		});

		//Instantiate the router
		var router = new Router();

		//Define some routes with their respective callback function
		router.route('/*path', function(path)
		{
			console.log(arguments);
			//$('#target').attr('src', '/' + window.location.hash.substr(1));
			//$('#target').attr('src', path);
		});

		// clear and reset history
		if(History.getCurrentIndex() !== 0)
		{
			window.location.replace('/doodles/');
		}

		// Start the router
		router.start();



	</script>

@endsection
