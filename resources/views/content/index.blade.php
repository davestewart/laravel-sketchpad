@extends('doodle::layout')

@section('content')

	@include('doodle::content.header')

	<div id="app">

		<div class="container">

			<div class="row">


				<div id="nav" class="col-md-4" style="margin-top:100px;">

					<div style="">

						<!--
						<ul class="breadcrumb">
							@foreach($folder->parents as $name => $route)
								<li><a class="folder" href="/{{ $route }}">{{ $name }}</a></li>
							@endforeach
							<li class="active">{{ $folder->name }}</li>
						</ul>
						-->

						<div id="controllers" class="col-md-6">
							@include('doodle::nav.folder')
						</div>

						@if(isset($controller))
							<div id="methods" class="col-md-6">
								@include('doodle::nav.methods')
							</div>
						@endif


					</div>

				</div>

				<div class="col-md-8">

					<section id="info">
						<h1>Title</h1>
						<p>Comment</p>
					</section>



					<section id="result">
						<iframe id="frame" name="frame" src="" frameborder="0"></iframe>
					</section>

				</div>
			</div>

		</div>

	</div>



	<script>

		// elements
		var $nav        = $('#nav');
		var $controllers= $('#controllers');
		var $methods    = $('#methods');
		var $result     = $('#result');
		var $info       = $('#info');

		// data
		var controller  = JSON.parse('{!! str_replace('\\', '\\\\', json_encode($controller)) !!}');

		// navigation
		$nav.on('click', 'a', function(event)
		{
			// prevent default

			// debug
			console.log(this);

			// variables
			var $link   = $(this);
			var url     = $link.attr('href');

			// route
			//router.navigate(url);

			/*
			// do something with content
			if($link.is('.folder'))
			{
				$.get(url + '?nav=1', function(html){
					$nav.html(html);
				});
			}
			else
			*/

			if($link.is('.method'))
			{
				event.preventDefault();

				$link
					.parent()
					.addClass('active')
					.siblings()
					.removeClass('active');

				var index   = $link.parent().index();
				var method  = controller.methods[index];
				var comment = method.comment.intro;

				$.get(url + '?run=1', function(html)
				{
					$result.html(html);

					$info.find('h1').text($link.text());
					$info.find('p').text(comment);


				});
			}
		});


		$methods.find('li:first-child a').trigger('click');

		/*
		// clear and reset history
		if(History.getCurrentIndex() !== 0)
		{
			window.location.replace('/doodles/');
		}

		// routing
		var router = new Router();
		router.route('/*path', function(path)
		{
			console.log(arguments);
			//$('#target').attr('src', '/' + window.location.hash.substr(1));
			//$('#target').attr('src', path);
		});
		router.start();
		*/

	</script>

@endsection
