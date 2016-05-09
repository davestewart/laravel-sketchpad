
<div id="app" class="row">

	<div id="nav" class="col-md-4">

		<div id="sticky">

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

	<div class="col-md-8">

		<result v-ref:result>
			Result template
		</result>

	</div>

</div>


<template id="result-template">

	<!-- info -->
	<section id="info">

		<header>
			<h1>{{ title }}</h1>
			<p class="info">{{ info || '&nbsp;' }}</p>
		</header>

		<div id="params">
			<nav class="navbar navbar-default">
				<ul class="nav navbar-nav">
					<li v-for="param in method.params">
						<label>{{ param.name }}</label>
						<input type="{{ getParamType(param) }}" v-model="method.params[$index].value" value="{{ param.value }}" lazy>
					</li>
					<li style="visibility: hidden"><label>No params</label><input type="text"></li>
				</ul>
			</nav>
		</div>

	</section>

	<!-- output -->
	<section id="output" :class="{loading:loading}" data-format="{{ format }}">

	</section>

</template>