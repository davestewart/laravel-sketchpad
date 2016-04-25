<div class="breadcrumb">
	@foreach($data->parents as $name => $route)
		<a class="folder" href="/{{ $route }}">{{ $name }}</a> /
	@endforeach
	<span>{{ $data->filename }}</span>
</div>

@if($data->folders)
	<div class="folders">
		<ul>
			@foreach($data->folders as $folder)
				<li><a class="folder" href="/{{ $folder->route }}">{{ $folder->filename }}</a></li>
			@endforeach
		</ul>
	</div>
@endif

@if($data->controllers)
<div class="controllers">
	@foreach($data->controllers as $controller)
		@include('doodle::nav.controller')
	@endforeach
</div>
@endif
