<ul class="nav nav-pills nav-stacked">
	@foreach($routes as $route => $reference)
		@if($reference instanceof \davestewart\doodle\objects\route\ControllerReference)
			<?php
			$text   = str_replace('/', ' <span>&#9656;</span> ', str_replace('doodles/', '', trim($route, '/') ));
			$active = $reference->route === $uri ? 'active' : '';
			//pr($reference);
			?>
			<li class="{{ $active }}">
				<a class="folder" href="/{{ $route }}">
					<span>{!! $text !!}</span>
					<!--<span class="badge badge-right">12</span>-->
				</a>
			</li>
		@endif
	@endforeach
</ul>
