<div id="app">

	<div class="container">

		<div class="row">

			<div id="nav" class="col-md-4">
				{!! $vue !!}
			</div>

			<div class="col-md-8">

				<section id="info">
					<h1>Sketchpad</h1>
					<p class="info">&nbsp;</p>
				</section>

				<section id="output">
					<pre>@{{ $data | json }}</pre>
				</section>

			</div>
		</div>

	</div>

</div>

