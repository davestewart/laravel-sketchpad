import Vue 		from 'vue';

// objects
import server	from './server/server.js';
import Timer	from '../classes/timer.js';
import state    from '../state/state'

const Loader = Vue.extend({

	data ()
	{
		return {
			loading		:false,
			transition	:false,
			method	    :null
		}
	},

	props: ['active'],

	computed:
	{
		defer ()
		{
			if(state.method)
			{
				const tags = state.method.tags;
				return tags.defer || tags.warning;
			}
			return false;
		}

	},

	created ()
	{
		this.timer = new Timer();
	},

	methods:
	{
		// ------------------------------------------------------------------------------------------------
		// main methods

			load ()
			{
				if (!state.method)
				{
					//this.$emit('error', 'No such method');
					return;
				}

				// properties
				this.transition	= state.method !== this.method;
				this.loading = ! this.defer;

				// load
				if(this.loading)
				{
					this.run();
				}
				else
				{
					if(state.method.tags.warning)
					{
						//alert(state.method.tags.warning || 'Be careful when running this method!');
					}
				}
			},

			run ()
			{
				this.$emit('start', this.transition);
				this.unwatch();
				this.$nextTick(() =>
				{
					this.timer.start();
					server.run(state.method, this.onLoad, this.onFail, this.onComplete);
					if(state.method)
					{
						this.watch();
					}
				});
			},

			watch ()
			{
				this.unwatch = this.$watch('state.method', this.onParamsChange, {deep:true});
			},

			unwatch ()
			{
				// will be populate by $watch
			},


		// ------------------------------------------------------------------------------------------------
		// events

			onParamsChange ()
			{
				if(state.method)
				{
					this.$emit('params');
				}
			},

			onLoad (data, status, xhr)
			{
				// debug
				//console.log([data, status, xhr.getAllResponseHeaders(), xhr]);

				// variables
				const type = xhr.getResponseHeader('Content-Type');
				if (state.method)
				{
					state.method.error = 0;
				}
				this.$emit('load', data, type);
			},

			onFail (xhr, status, message)
			{
				if (state.method)
				{
					state.method.error = 1;
				}
				var data	= xhr.responseText;
				var type	= xhr.getResponseHeader('Content-Type');
				this.$emit('error', data, type);
			},

			onComplete ()
			{
				console.info('Ran "%s" in %d ms', state.route, this.timer.stop().time);
				this.method 	= state.method;
			},

		// ------------------------------------------------------------------------------------------------
		// events

			onStoreLoad (event)
			{
				if(state.controller && state.controller.relpath == event.path)
				{
					var cIndex 	= event.index;
					var mIndex	= state.method ? state.controller.methods.indexOf(state.method) : -1;
					if(cIndex > -1)
					{
						this.unwatch();
						state.controller = this.store.controllers[cIndex];
						if(mIndex > -1)
						{
							state.method = state.controller.methods[mIndex];
						}
						this.watch();
					}

					// reload
					this.update();
				}
			}

	}

});

export default new Loader();