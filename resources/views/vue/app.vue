<div id="app" class="row">

	<div id="nav" class="col-md-4">

		<!-- controllers -->
		<div id="controllers" class="col-md-6">

			<ul class="nav nav-pills nav-stacked">
				<li
					v-for="controller in controllers"
					class="{{ isActive(controller.route) ? 'active' : ''}}"
					@click.prevent="loadController(controller)"
					>
					<a
						class="controller"
						data-name="{{ controller.class }}"
						href="{{ controller.route }}"
						>
						{{{ getLinkHtml(controller.route) }}}
					</a>
				</li>
			</ul>
		</div>

		<!-- methods -->
		<div id="methods" class="col-md-6">
			<ul class="nav nav-pills nav-stacked">
				<li
					v-for="method in controller.methods"
					class="{{ isActive(method.route) ? 'active' : ''}}"
					@click.prevent="loadMethod(method, this)"
					>
					<a
						class="method"
						title="{{ method.label }}"
						href="{{ method.route }}"
						>
						{{ method.label }}
					</a>
					<p v-if="method.comment.intro">{{ method.comment.intro }}</p>
				</li>
			</ul>
		</div>

	</div>

	<div class="col-md-8">

		<!-- info -->
		<section id="info">
			<h1>{{ method.label || 'Sketchpad' }}</h1>
			<p class="info">{{ method.comment ? method.comment.intro : method.label}}</p>
		</section>

		<!-- output -->
		<section id="output">
			<pre>@{{ $data | json }}</pre>
		</section>

	</div>

</div>
