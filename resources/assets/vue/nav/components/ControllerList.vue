<template>

	<div class="controller-list">
		<ul class="nav nav-pills nav-stacked">
			<template v-for="result in results">
				<folder :route="result.route"></folder>
				<method
					v-for="method in result.methods"
					:method="method">
				</method>
			</template>
		</ul>
	</div>

</template>

<script>

import store    from '../../../js/state/store'
import settings from '../../../js/state/settings'
import Helpers  from '../../../js/classes/helpers'

import Folder   from './Folder.vue'
import Method   from './Method.vue'

export default
{
	props: ['filter'],

	components:
	{
		Folder,
		Method
	},

	computed:
	{
		filterFn ()
		{
			return typeof this.filter === 'function'
				? this.filter
				: method => true // default to all
		},

		results ()
		{
			const results = [];
			store.controllers.forEach(controller => {
				const methods = controller.methods.filter(this.filterFn)
				if (methods.length)
				{
					results.push({route:controller.route, methods:methods})
				}
			});
			return results;
		}
	}
}

</script>

<style lang="scss">
	
</style>