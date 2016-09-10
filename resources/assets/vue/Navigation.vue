<template>

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

<script>

import Method		from './Method.vue';
import Helpers		from '../js/classes/helpers.js';

export default
{
	components:
	{
		Method
	},

	props:
	[
		'controllers',
		'state'
	],

	filters:
	{
		humanize:Helpers.humanize
	},

	ready:function()
	{
		this.$watch('state.controller', function ()
		{
			//$(this.$el).find('a[title]').tooltip({container:'body', trigger:'hover', placement:'right', delay: { "show": 500, "hide": 100 }})
		});

	},

	methods:
	{
		getLabel:function(obj)
		{
			return Helpers.getControllerLabel(obj);
		},

		getLinkHtml:function(route)
		{
			var name 	= '<span class="name">';
			var divider	= '<span class="divider">&#9656;</span> ';
			var close	= '</span> ';

			return name + route
				.replace('/sketchpad/', '')
				.replace(/\/$/, '')
				.split('/')
				.join(close + divider + name) + close;
		},

		isActive:function(route)
		{
			return this.state.route && this.state.route.indexOf(route) == 0;
		}

	}

}

</script>

<style lang="scss">
	
</style>