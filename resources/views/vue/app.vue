<div>

	<div id="controllers" class="col-md-6">

		<ul class="nav nav-pills nav-stacked">
			<li
				v-for="controller in controllers"
				class="{{ isActive(controller.route) ? 'active' : ''}}"
				@click="loadController(controller)"
				>
				<a
						class="controller"
						data-name="{{ controller.class }}"
						href="{{ controller.route }}">

					{{{ getLinkHtml(controller.route) }}}
				</a>
			</li>
		</ul>
	</div>

	<div id="methods" class="col-md-6">
		<div class="controller">
			<h4>{{ controller.label }}</h4>
			<ul class="nav nav-pills nav-stacked">
				<li
					v-for="method in controller.methods"
					class="{{ isActive(method.route) ? 'active' : ''}}"
					@click="loadMethod(method, this)"
				>
					<a
						class="method"
						title="{{ method.label }}"
						href="{{ method.route }}"
					>{{ method.label }}</a>

					<p v-if="method.comment.intro">{{ method.comment.intro }}</p>
				</li>
			</ul>
		</div>
	</div>

</div>
