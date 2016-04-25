<ul class="controller">
	<li>
		<h4>{{ $controller->label }}</h4>
		<ul>
			@foreach($controller->methods as $method)
				<li><a class="method" title="{{ $method->label }}" data-link="{{ $method->route }}" target="target" href="/{{ $method->route }}">{{ $method->name }}</a></li>
			@endforeach
		</ul>
	</li>
</ul>