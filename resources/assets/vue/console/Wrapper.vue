<template>

	<div>
		<console v-if="controller" v-ref:console></console>
		<error v-else></error>
	</div>

</template>

<script>

// objects
import store		from '../../js/state/store.js';
import state		from '../../js/state/state.js';
import watcher	    from '../../js/services/watcher';

// components
import Error        from './Error.vue';
import Console	    from './Console.vue';

export default
{
	name: 'Wrapper',

	components:
	{
		Error,
		Console
	},

	data ()
	{
		return {
			state: state,
			controller: null
		}
	},

// ---------------------------------------------------------------------------------------------------------------------
// lifecycle

	ready ()
	{
		state.$on('reset', this.onStateReset)
		store.$on('change', this.onStoreChange)
	},

	created ()
	{
		store.$off(this.onStoreChange)
	},


// ---------------------------------------------------------------------------------------------------------------------
// route

	route:
	{
		canReuse: true,

		activate (transition)
		{
			this.$nextTick(transition.next)
		},

		data (transition)
		{
			// skip updating if we're updating parameters internally
			if (this.$refs.console && this.$refs.console.updating)
			{
				return transition.next()
			}

			// update
			this.update()

			// complete
			transition.next();
		}
	},

	methods:
	{
		update ()
		{
			// update from route
			state.setRoute(this.$route.params.route, this.$route.query);

			// variables
			const controller = state.controller
			const method = state.method

			// missing controller
			if (!controller)
			{
				this.controller = null
				return
			}

			if (!controller.error)
			{
				this.controller = controller
				const callback = () => this.$refs.console.setData(controller, method)
				this.$refs.console
					? callback ()
					: this.$nextTick(callback)
			}
			else
			{
				this.controller = null
			}
		},

		onStoreChange ()
		{
			// console.log('wrapper: store change');
			this.update()
		},

		onStateReset ()
		{
			// console.log('wrapper: state reset');
			this.update()
		}
	}

}

</script>

<style lang="scss">

</style>