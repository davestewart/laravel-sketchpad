@extends('doodle::layout')

@section('content')

	@include('doodle::content.header')

	<div id="app">

		<div class="container">

			<div class="row">

				<div id="nav" class="col-md-4" style="margin-top:100px;">

					<div style="">

						<div id="controllers" class="col-md-6">
							@include('doodle::nav.folder')
						</div>

						@if(isset($controller))
							<div id="methods" class="col-md-6">

							</div>
						@endif


					</div>

				</div>

				<div class="col-md-8">

					<section id="info">
						<h1>Sketchpad</h1>
						<p>&nbsp;</p>
					</section>

					<section id="result">
						@include('doodle::pages.welcome')
					</section>

				</div>
			</div>

		</div>


		<script id="controller" type="text/plain">
			@if(isset($controller))
				{!! str_replace('\\', '\\\\', json_encode($controller, JSON_UNESCAPED_SLASHES)) !!}
			@endif
		</script>

	</div>

	<a data-toggle="modal" href="http://fiddle.jshell.net/bHmRB/51/show/" data-target="#myModal">Click me !</a>

	<!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content" style="width:500px;">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Modal tfhgfghfgh</h4>

				</div>
				<div class="modal-body"><div class="te"></div></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Save changes</button>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->


@endsection
