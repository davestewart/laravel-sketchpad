<template>

	<div id="console" :class="{loading: loading, transitioning: transitioning, pending: pending, error: error}">
		<info v-ref:info :controller="controller" :method="method"></info>
		<params v-ref:params :method="method" :params="params" :run-if="runIf" :run-state="runState"></params>
		<output v-ref:output v-show="!iframe" :method="method"></output>
		<x-frame v-ref:xframe v-show="iframe"></x-frame>
	</div>

</template>

<script>

// utils
import _            from 'underscore'
import {clone,
		scrollTo}   from '../../js/functions/utils.js';
import Helpers      from '../../js/functions/helpers.js';

// objects
import settings		from '../../js/state/settings.js';
import state		from '../../js/state/state.js';
import server		from '../../js/services/server.js';
import watcher	    from '../../js/services/watcher';

// components
import Info	        from './Info.vue';
import Params		from './Params.vue';
import Output		from './Output.vue';
import XFrame       from './XFrame.vue';

export default
{
	name: 'Console',

	components:
	{
		Info,
		Params,
		Output,
		XFrame,
	},

	data ()
	{
		return {
			// objects
			controller: null,
			method: null,
			params: null,

			// behaviour
			routing: false,
			updating: false,
			pending: false,
			loading: false,
			transitioning: false
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
		},

		iframe ()
		{
			return this.method && this.method.tags.iframe;
		},

		runIf ()
		{
			return this.method && this.method.runIf
		},

		runState ()
		{
			return this.method
				? this.method.runState
				: true
		}
	},


// ---------------------------------------------------------------------------------------------------------------------
// lifecycle

	created ()
	{
		this.run = _.debounce(this.run, 0)
		//this.update = _.debounce(this.update, 400)
		state.$on('update', this.onStateUpdate)
		watcher.addHandler(this.onFileChange, file => !/Controller\.php$/.test(file));
	},

	ready ()
	{
		this.$refs.params.$on('run', this.onRunClick)
		this.$refs.params.$on('runState', this.onToggleRunState)
		this.$refs.output.$on('submit', this.onFormSubmit)
		this.$watch('params', this.onParamsChange, {deep: true})
	},

	destroyed ()
	{
		state.$off('change', this.onStateUpdate)
		watcher.removeHandler(this.onFileChange);
	},


// ---------------------------------------------------------------------------------------------------------------------
// methods

	methods:
	{

		// ------------------------------------------------------------------------------------------------
		// routing actions

			setData (controller, method)
			{
				// routing
				this.routing = true

				// set changed properties
				if (controller !== this.controller)
				{
					this.clear()
					this.controller = controller
				}
				if (method !== this.method)
				{
					Helpers.setTitle('Run ▸ ' + state.route
							.replace(/\?.+$/, '')
							.replace(/\/$/, '')
							.replace(/\//g, ' ▸ '));
					this.transitioning = true
					this.method = method
					if ((method && method.tags.append) || settings.ui.appendOutput)
					{
						this.clear()
					}
					if (settings.ui.scrollTop)
					{
						scrollTo(0)
					}
				}
				this.params = method
					? clone(method.params)
					: null

				// load
				this.pending = this.defer
				if (this.method)
				{
					this.method.runState = false
					if (!this.defer)
					{
						this.run()
					}
					else
					{
						if (this.transitioning)
						{
							this.$nextTick(() => this.$refs.output.setContent('Waiting for input...'))
						}
					}
				}
			},


		// ------------------------------------------------------------------------------------------------
		// loading actions

			run ()
			{
				if (this.method && !this.controller.error)
				{
					this.loading = true
					if (this.iframe)
					{
						this.$refs.xframe.load(this.method);
						this.onComplete();
					}
					else
					{
						server.run(this.method, this.onLoad, this.onFail, this.onComplete);
					}
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

				// run if we're not deferred
				if (!this.defer || force)
				{
					this.run()
				}
			},

			clear ()
			{
				this.$refs.xframe.clear();
				this.$refs.output.clear();
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
				// console.log('console: file change')
				this.run();
				return true;
			},

			onStateUpdate (controller, method)
			{
				// console.log('console: state update')
				this.method = method;
				this.params = clone(method.params);
			},


		// -------------------------------------------------------------------------------------------------------------
		// handlers

			onParamsChange ()
			{
				if (this.method)
				{
					this.method.runState = false;
				}
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

			onRunClick ()
			{
				this.pending
					? this.update(true)
					: this.run()
			},

			onToggleRunState ()
			{
				this.method.runState = ! this.method.runState
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