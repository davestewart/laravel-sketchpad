<template>

	<div>
		<console v-if="isValid" v-ref:console></console>
		<error v-else :controller="controller"></error>
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

	computed:
	{
		isValid ()
		{
			return this.controller && !this.controller.error;
		}
	},


// ---------------------------------------------------------------------------------------------------------------------
// lifecycle

	ready ()
	{
		state.$on('reset', this.onStateReset)
		store.$on('change', this.onStoreChange)
		store.$on('add', this.onStoreAdd)
	},

	created ()
	{
		store.$off(this.onStoreChange)
	},

	destroyed ()
	{
		state.reset()
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
			const route = transition.to;
			state.setRoute(route.params.route, route.query);
			this.update()

			// complete
			transition.next();
		}
	},

	methods:
	{
		update ()
		{
			this.controller = state.controller
			if (this.isValid)
			{
				const callback = () => this.$refs.console.setData(state.controller, state.method)
				this.$refs.console
					? callback ()
					: this.$nextTick(callback)
			}
		},

		onStoreChange ()
		{
			// console.log('wrapper: store change');
			this.update()
		},

		onStoreAdd (controller)
		{
			// console.log('wrapper: store add');
			// re-add deleted controller
			if (!state.controller && this.$route.params.route === controller.route)
			{
				state.controller = controller;
			}
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