<template>

	<div :class="{'controller-list': true, comments:settings.ui.showComments}">
		<ul class="nav nav-pills nav-stacked">
			<template v-for="result in results">
				<li class="folder">
					<span class="name">
						{{{ getFolderHtml(result.controller.route) }}}
					</span>
				</li>
				<li v-for="method in result.methods">
					<a v-link="'/run/' + method.route">{{{ getLabel(method) }}}</a>
					<p v-if="method.comment.intro" class="comment">{{ method.comment.intro }}</p>
				</li>
			</template>
		</ul>
	</div>

</template>

<script>

import store    from '../../js/state/store'
import settings from '../../js/state/settings'
import Helpers  from '../../js/classes/helpers'
	
export default
{
	props: ['filter'],

	data ()
	{
		return {
			settings:settings,
			controllers: app.store.controllers
		}
	},

	computed:
	{
		filterFn ()
		{
			return typeof this.filter === 'function'
				? this.filter
				: method => true
		},

		results ()
		{
			const fn = this.filterFn
			console.log('FAVOURITES!', fn)
			const results = [];
			this.controllers.forEach(controller => {
				const methods = controller.methods.filter(fn)
				if (methods.length)
				{
					results.push({controller:controller, methods:methods})
				}
			});
			return results;
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
		}

	}
}

</script>

<style lang="scss">
	
</style>