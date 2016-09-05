
<div id="app" class="row">

	<div class="col-xs-4">
		<navigation v-ref:navigation :controllers="store.controllers" :state="state" >
			Navigation
		</navigation>
	</div>

	<div class="col-xs-8">
		<result v-if="state.controller" v-ref:result :state="state">
			Result
		</result>
		<div id="content" v-else>

		</div>
	</div>

	<modal v-ref:modal></modal>

</div>


<template id="navigation-template">

	<div id="nav">

		<div class="sticky">

			<!-- controllers -->
			<div id="controllers" class="col-xs-6">

				<ul class="nav nav-pills nav-stacked">

					<template v-for="controller in controllers">

						<li
							v-if="! $index || controllers[$index -1].folder !== controllers[$index].folder"
							class="folder"
							>
							{{{ getLinkHtml(controller.folder) }}}
						</li>
						<li
							:class="{ controller:true, active:isActive(controller.route) }"
							>
							<a
								data-name="{{ controller.class }}"
								data-path="{{ controller.path }}"
								href="{{ controller.route }}"
								>
								{{{ getLabel(controller) }}}
							</a>
						</li>
					</template>

				</ul>
			</div>

			<!-- methods -->
			<div id="methods" class="col-xs-6">
				<ul v-if="state.controller" class="nav nav-pills nav-stacked">
					<method v-if="method && method.name != 'index' " :method="method" :state="state" v-for="method in state.controller.methods"></method>
				</ul>
			</div>

		</div>

	</div>

</template>


<template id="method-template">

	<li v-if="tags.group" class="folder">
		<span class="name">{{ tags.group }}</span>
	</li>

	<li
		:style="listStyle"
		:class="listClass"
		>
		<a
			:class="{method:true, error:error}"
			:style="linkStyle"
			title="{{ comment.intro }}"
			href="{{ state.makeRoute(method) }}"
			>
			{{{ label }}}
		</a>
		<p
			v-if="comment.intro && $root.settings.showComments"
			class="comment"
			>{{ comment.intro }}</p>
	</li>
</template>



<template id="result-template">

	<div id="result" :class="{loading:loading, transition:transition}">

		<!-- info -->
		<section id="info">

			<header>
				<h1>{{ title }}</h1>
				<div :class="{info:true, alert:alert, 'alert-danger':warning, 'alert-info':archived }">{{{ info | marked }}}</div>
			</header>

			<!-- parameters -->
			<div id="params" v-if="state.method && state.method.name != 'index'">
				<div class="sticky">

					<nav v-if="params" class="navbar navbar-default">
						<span class="loader"></span>
						<ul class="nav navbar-nav">
							<li v-for="param in params">
								<param :param="param"></param>
							</li>
							<!--<li v-if="! deferred && params.length == 0"><span>No parameters</span></li>-->
							<li><button @click="_load()" class="btn btn-xs" style="outline:none">Run</button></li>
						</ul>
					</nav>

				</div>
			</div>
		</section>

		<!-- output -->
		<section id="output" data-format="{{ format }}"></section>

	</div>

</template>

<template id="param-template">

	<label
		for="{{ id }}"
		:title="param.text"
	>{{ param.name }}</label>
	<input

		:id="id"
		:type="type"
		v-model="value"
		debounce="400"
	>

</template>




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

