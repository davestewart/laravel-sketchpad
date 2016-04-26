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

@endsection
