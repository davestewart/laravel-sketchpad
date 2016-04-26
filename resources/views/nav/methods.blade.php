<ul class="nav nav-pills nav-stacked">
	@foreach($controller->methods as $method)
		<li>
			<a
				class="method"
				title="{{ $method->label }}"
				data-link="{{ $method->route }}"
				href="/{{ $method->route }}"
			>{{ $method->name }}()</a>
		</li>
	@endforeach
</ul>
