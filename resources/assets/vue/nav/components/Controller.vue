<template>

	<li
		:class="{ controller:true, error: !! controller.error, icon: !!controller.error, active:isActive() }"
		>
		<span v-if="controller.error"
		    :title="controller.error"
			:data-path="controller.path"
			:data-route="controller.route"
			>
			{{{ getLabel() }}}
		</span>
		<a v-else
			:data-name="controller.class"
			:data-path="controller.path"
			:data-route="controller.route"
			v-link="'/run/' + controller.route"
			>
			{{{ getLabel() }}}
		</a>
	</li>

</template>

<script>

import Helpers		from '../../../js/functions/helpers.js';
import settings 	from '../../../js/state/settings.js';
import state 	    from '../../../js/state/state.js';

export default
{
	props:['controller'],

	methods:
	{
		getLabel ()
		{
			return settings.ui.humanizeText
				? Helpers.humanize(this.controller.label)
				: this.controller.label;
		},

		isActive ()
		{
			return state.route && state.route.indexOf(this.controller.route) == 0;
		}

	}

}

</script>

<style lang="scss">
	
</style>