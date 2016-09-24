@extends('sketchpad::layout')

@section('content')

	@include('sketchpad::elements.header')

	<div class="container">

		<app></app>

	</div>

	<script id="data" type="text/plain">
		{!! json_encode($data, JSON_UNESCAPED_SLASHES) !!}
	</script>

	<div id="welcome">
		@include('sketchpad::pages.welcome')
	</div>

	<!--
	<a data-toggle="modal" href="http://fiddle.jshell.net/bHmRB/51/show/" data-target="#modal">Click me !</a>
	-->

@endsection
