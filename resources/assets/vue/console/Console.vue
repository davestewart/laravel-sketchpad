<template>

	<div id="console" :class="{loading: loading, transitioning: transitioning, pending: pending, error: error}">
		<div v-show="controller">
			<info v-ref:info :controller="controller" :method="method"></info>
			<params v-ref:params :method="method" :params="params"></params>
			<output v-ref:output :method="method"></output>
		</div>
		<not-found v-show="!controller"></not-found>
	</div>

</template>

<script>

// utils
import _            from 'underscore'
import {clone}      from '../../js/functions/utils.js';

// objects
import state		from '../../js/state/state.js';
import server		from '../../js/services/server.js';
import watcher	    from '../../js/services/watcher';

// components
import NotFound     from './NotFound.vue';
import Info	        from './Info.vue';
import Params		from './Params.vue';
import Output		from './Output.vue';

export default
{
	name: 'Console',

	components:
	{
		NotFound,
		Info,
		Params,
		Output,
	},

	data ()
	{
		return {
			routing: false,
			updating: false,
			pending: false,
			loading: false,
			transitioning: false,
			controller: null,
			method: null,
			params: null,
		}
	},

	computed:
	{
		defer ()
		{
			const tags = this.method ? this.method.tags : {}
			return !! (tags.defer || tags.warn)
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
		this.update = _.debounce(this.update, 400)
		state.$on('update', this.onStateUpdate)
		watcher.addHandler(/./, this.onFileChange);
	},

	ready ()
	{
		this.$refs.params.$on('load', this.onLoadClick)
		this.$refs.output.$on('submit', this.onFormSubmit)
		this.$watch('params', this.onParamsChange, {deep: true})
	},

	destroyed ()
	{
		state.reset()
		state.$off('change', this.onStateUpdate)
		watcher.removeHandler(this.onFileChange);
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
			if (this.updating)
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

			// missing controller
			if (!controller)
			{
				this.controller = false
				return transition.next()
			}

			// set changed properties
			if (controller !== this.controller)
			{
				this.controller = controller
			}
			if (method !== this.method)
			{
				this.transitioning = true
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
				if (this.transitioning)
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
				this.updating = true
				router.replace('/run/' + state.route);
				this.updating = false

				// load if we're not deferred
				if (!this.defer || force)
				{
					this.load()
				}
			},


		// ------------------------------------------------------------------------------------------------
		// loading events

			onLoad (text, status, xhr)
			{
				const type = xhr.getResponseHeader('Content-Type');
				if (state.method)
				{
					state.method.error = 0;
				}
				this.$refs.output.setContent(text, type)
			},

			onFail (xhr, status, message)
			{
				// variables
				const text	= xhr.responseText;
				const type	= xhr.getResponseHeader('Content-Type');
				const error = $(text).find('.exception_title').text()

				// reload if token exception
				if (/TokenMismatchException/.test(error))
				{
					this.$refs.output.setContent('<p>CSRF token expired. Click <a href="javascript:location.reload()">here</a> to reload the page...</p>')
					return
				}

				// show error
				if (state.method)
				{
					state.method.error = 1;
				}
				this.$refs.output.setError(text, type)
			},

			onComplete ()
			{
				this.routing = false
				this.loading = false
				this.pending = false
				this.transitioning = false
			},

			onFileChange (file, type)
			{
				this.load();
				return true;
			},

			onStateUpdate (controller, method)
			{
				this.method = method;
				this.params = clone(method.params);
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