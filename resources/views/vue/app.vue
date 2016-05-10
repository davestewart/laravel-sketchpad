
<div id="app" class="row">

	<div class="col-md-4">

		<navigation v-ref:navigation>
			Navigation
		</navigation>

	</div>

	<div class="col-md-8">

		<result v-ref:result>
			Result
		</result>
	</div>

	<modal v-ref:modal></modal>

</div>


<template id="modal-template">

	<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3 class="modal-title">{{{ title }}}</h3>

				</div>
				<div class="modal-body">{{{ body }}}</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					<button v-if="save" type="button" class="btn btn-primary">OK</button>
				</div>
			</div>
		</div>
	</div>

</template>

<template id="navigation-template">

	<div id="nav">

		<div class="sticky">

			<!-- controllers -->
			<div id="controllers" class="col-md-6">

				<ul class="nav nav-pills nav-stacked">
					<li
						v-for="controller in controllers"
						:class="{ active:isActive(controller.route) }"
						@click.prevent="onControllerClick(controller)"
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
				<ul v-if="controller" class="nav nav-pills nav-stacked">
					<li
						v-for="method in controller.methods"
						class="{{ isActive(method.route) ? 'active' : ''}}"
						@click.prevent="onMethodClick(method, this)"
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

	</div>

</template>



<template id="result-template">

	<!-- info -->
	<section id="info">

		<header>
			<h1>{{ title }}</h1>
			<p class="info">{{ info || '&nbsp;' }}</p>
		</header>

		<!-- parameters -->
		<params v-ref:params></params>


	</section>

	<!-- output -->
	<section id="output" :class="{loading:loading}" data-format="{{ format }}"></section>

</template>



<template id="params-template">

	<div id="params">
		<div class="sticky">

			<nav v-if="params" class="navbar navbar-default">
				<ul class="nav navbar-nav">
					<li v-for="param in params">
						<label>{{ param.name }}</label>
						<input
							type="{{ getType(param) }}"
							v-model="params[$index].value"
							value="{{ param.value }}"
							@keyup="onParamChange | debounce 400"
						>
					</li>
					<li v-if="params.length == 0"><span>No parameters</span></li>
				</ul>
			</nav>

		</div>
	</div>

</template>