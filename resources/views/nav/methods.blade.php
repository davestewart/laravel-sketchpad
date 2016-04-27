<ul class="nav nav-pills nav-stacked">
	@foreach($controller->methods as $method)
		<li>
			<a
				class="method"
				title="{{ $method->label }}"
				href="/{{ $method->route }}"
			>{{ $method->name }}()</a>
			<p>{{ $method->comment->intro }}</p>
		</li>
	@endforeach
</ul>
