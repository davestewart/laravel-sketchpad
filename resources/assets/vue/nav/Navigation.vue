<template>

	<div id="nav" :class="{'controller-list':true, comments:settings.ui.showComments}">

		<div class="sticky">

			<!-- controllers -->
			<section id="controllers" class="col-xs-6">

				<ul class="nav nav-pills nav-stacked">

					<template v-for="controller in controllers">

						<li
							v-if="! $index || controllers[$index -1].folder !== controllers[$index].folder"
							class="folder"
							>
							{{{ controller.methods[0].name }}}
							{{{ getFolderHtml(controller.folder) }}}
						</li>
						<li
							:class="{ controller:true, active:isActive(controller.route) }"
							>
							<a
								:data-name="controller.class"
								:data-path="controller.path"
								:data-route="controller.route"
								v-link="'/run/' + controller.route"
								>
								{{{ getLabel(controller) }}}
							</a>
						</li>
					</template>

				</ul>
			</section>

			<!-- methods -->
			<section id="methods" class="col-xs-6">
				<ul v-if="state.controller" class="nav nav-pills nav-stacked">
					<method
						v-for="method in methods"
						:method="method"
						:state="state"
					></method>
				</ul>
			</section>

		</div>

	</div>

</template>

<script>

import Helpers		from '../../js/classes/helpers.js';

import Method		from './Method.vue';

export default
{
	components:
	{
		Method
	},

	props:
	[
		'settings',
		'controllers',
		'state'
	],

	filters:
	{
		humanize:Helpers.humanize
	},

	ready ()
	{
		/*
		this.$watch('state.controller', function ()
		{
			$(this.$el).find('a[title]').tooltip({container:'body', trigger:'hover', placement:'right', delay: { "show": 500, "hide": 100 }})
		});
		*/
		//this.$watch('controllers', value => this.$forceUpdate())
	},

	computed:
	{
	    methods ()
	    {
	        return this.state.controller.methods.filter(method => method.name != 'index')
	    }

	},

	methods:
	{
		getLabel (obj)
		{
			return Helpers.getControllerLabel(obj);
		},

		getFolderHtml (route)
		{
			return Helpers.getFolderHtml(route);
		},

		isActive (route)
		{
			return this.state.route && this.state.route.indexOf(route) == 0;
		}

	}

}

</script>

<style lang="scss">
	
</style>