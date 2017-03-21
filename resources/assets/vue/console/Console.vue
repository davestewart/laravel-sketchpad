<template>

	<div id="console" :class="{loading: loading, transition: transition, pending: pending, error: error}">
		<info v-ref:info :controller="controller" :method="method"></info>
		<params v-ref:params :method="method" :params="params"></params>
		<output v-ref:output :method="method"></output>
	</div>

</template>

<script>

// utils
import _            from 'underscore'
import {clone,dump} from '../../js/functions/utils.js';

// objects
import state		from '../../js/state/state.js';
import server		from '../../js/services/server.js';
import watcher	    from '../../js/services/watcher';

// components
import Info		    from './Info.vue';
import Params		from './Params.vue';
import Output		from './Output.vue';

export default
{
	name: 'Console',

	components:
	{
		Info,
		Params,
		Output,
	},

	data ()
	{
		return {
			routing: false,
			loading: false,
			pending: false,
			transition: false,
			controller: null,
			method: null,
			params: null,
			isUpdate: false
		}
	},

	computed:
	{
		defer ()
		{
			const tags = this.method ? this.method.tags : {}
			return !! (tags.defer || tags.warning)
		},

		error ()
		{
			return this.method && this.method.error
		}
	},

// ---------------------------------------------------------------------------------------------------------------------
// lifecycle

	created ()
	{
		watcher.addHandler(/\.php$/, this.onFileChange);
		this.update = _.debounce(this.update, 400)
	},

	ready ()
	{
		this.$refs.params.$on('load', this.onLoadClick)
		this.$refs.output.$on('submit', this.onFormSubmit)
		this.$watch('params', this.onParamsChange, {deep: true})
	},

	destroyed ()
	{
		watcher.removeHandler(this.onFileChange);
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
			// skip full update if we're updating parameters internally
			if (this.isUpdate)
			{
				return transition.next()
			}

			// set new route
			this.routing = true
			const route = transition.to;
			const updated = state.setRoute(route.params.route, route.query);

			// variables
			const controller = state.controller
			const method = state.method
			const params = state.method
				? clone(state.method.params)
				: null

			// set changed properties
			if (controller !== this.controller)
			{
				this.controller = controller
			}
			if (method !== this.method)
			{
				this.transition = true
				this.method = method
				this.$refs.output.clear()
				document.title = 'Sketchpad - ' + state.route.replace(/\/$/, '').replace(/\//g, ' ▸ ');
			}
			this.params = params

			// load
			this.pending = this.defer
			if (!this.defer)
			{
				this.load()
			}
			else
			{
				if (this.transition)
				{
					this.$nextTick(() => this.$refs.output.setContent('Waiting for input...'))
				}
			}

			// complete
			transition.next();
		}
	},


// ---------------------------------------------------------------------------------------------------------------------
// methods

	methods:
	{

		// ------------------------------------------------------------------------------------------------
		// loading actions

			load ()
			{
				if (this.method)
				{
					this.loading = true
					server.run(this.method, this.onLoad, this.onFail, this.onComplete);
				}
			},

			update (force)
			{
				// update params
				state.setParams(this.params)

				// update route
				this.isUpdate = true
				router.replace('/run/' + state.route);
				this.isUpdate = false

				// load if we're not deferred
				if (!this.defer || force)
				{
					this.load()
				}
			},


		// ------------------------------------------------------------------------------------------------
		// loading events

			onLoad (data, status, xhr)
			{
				const type = xhr.getResponseHeader('Content-Type');
				if (state.method)
				{
					state.method.error = 0;
				}
				this.$refs.output.setContent(data, type)
			},

			onFail (xhr, status, message)
			{
				if (state.method)
				{
					state.method.error = 1;
				}
				var data	= xhr.responseText;
				var type	= xhr.getResponseHeader('Content-Type');
				this.$refs.output.setError(data, type)
			},

			onComplete ()
			{
				this.routing = false
				this.loading = false
				this.pending = false
				this.transition = false
			},

			onFileChange (file, type)
			{
				this.load();
				return true;
			},


		// -------------------------------------------------------------------------------------------------------------
		// handlers

			onParamsChange ()
			{
				if (!this.routing)
				{
					if (this.defer)
					{
						this.pending = true
					}
					else
					{
						this.update()
					}
				}
			},

			onLoadClick ()
			{
				this.pending
					? this.update(true)
					: this.load()
			},

			onFormSubmit (data)
			{
				server.submit(this.method, data, this.onLoad)
			}
	}

}

</script>

<style lang="scss">
	
</style>