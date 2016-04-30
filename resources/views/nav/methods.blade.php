<ul class="nav nav-pills nav-stacked">
	@foreach($controller->methods as $method)
		<li>
			<a
				class="method"
				title="{{ $method->label }}"
				href="{{ $method->route }}"
			>{{ $method->label }}</a>
			@if($method->comment->intro)
			<p>{{ $method->comment->intro }}</p>
			@endif
		</li>
	@endforeach
</ul>
