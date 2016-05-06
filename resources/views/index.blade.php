@extends('sketchpad::layout')

@section('content')

	@include('sketchpad::elements.header')

	@include('sketchpad::vue.app')

	<script id="data" type="text/plain">
		{!! json_encode($data, JSON_UNESCAPED_SLASHES) !!}
	</script>

	<!--
	<a data-toggle="modal" href="http://fiddle.jshell.net/bHmRB/51/show/" data-target="#myModal">Click me !</a>
	-->

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
