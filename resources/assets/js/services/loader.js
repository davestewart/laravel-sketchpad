import Vue 		from 'vue';

// objects
import server		from './server/server.js';
import Timer		from '../classes/timer.js';

const Loader = Vue.extend({

	data ()
	{
		return {
			loading		:false,
			transition	:false,
			method	    :null
		}
	},

	props: ['state', 'active'],

	computed:
	{
		defer ()
		{
			if(this.state.method)
			{
				const tags = this.state.method.tags;
				return tags.defer || tags.warning;
			}
			return false;
		}

	},

	created ()
	{
		this.timer = new Timer();
		this.state = app.state;
	},

	methods:
	{
		// ------------------------------------------------------------------------------------------------
		// main methods

			load ()
			{
				if (!this.state.method)
				{
					//this.$emit('error', 'No such method');
					return;
				}

				// properties
				this.transition	= this.state.method !== this.method;
				this.loading = ! this.defer;

				// load
				if(this.loading)
				{
					this.run();
				}
				else
				{
					if(this.state.method.tags.warning)
					{
						//alert(this.state.method.tags.warning || 'Be careful when running this method!');
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
					server.run(this.state.method, this.onLoad, this.onFail, this.onComplete);
					if(this.state.method)
					{
						this.watch();
					}
				});
			},

			watch ()
			{
				this.unwatch = this.$watch('state.method.params', this.onParamsChange, {deep:true});
			},

			unwatch ()
			{
				// will be populate by $watch
			},


		// ------------------------------------------------------------------------------------------------
		// events

			onParamsChange ()
			{
				if(this.state.method)
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
				if (this.state.method)
				{
					this.state.method.error = 0;
				}
				this.$emit('load', data, type);
			},

			onFail (xhr, status, message)
			{
				if (this.state.method)
				{
					this.state.method.error = 1;
				}
				var data	= xhr.responseText;
				var type	= xhr.getResponseHeader('Content-Type');
				this.$emit('error', data, type);
			},

			onComplete ()
			{
				console.info('Ran "%s" in %d ms', this.state.route, this.timer.stop().time);
				this.method 	= this.state.method;
			},

		// ------------------------------------------------------------------------------------------------
		// events

			onStoreLoad (event)
			{
				if(this.state.controller && this.state.controller.relpath == event.path)
				{
					var cIndex 	= event.index;
					var mIndex	= this.state.method ? this.state.controller.methods.indexOf(this.state.method) : -1;
					if(cIndex > -1)
					{
						this.unwatch();
						this.state.controller = this.store.controllers[cIndex];
						if(mIndex > -1)
						{
							this.state.method = this.state.controller.methods[mIndex];
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