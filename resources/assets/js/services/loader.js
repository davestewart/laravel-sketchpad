import Vue 		from 'vue';

// objects
import server	from './server/server.js';
import watcher	from './watcher';
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
		watcher.addHandler(/\.php$/, this.onFileChange)
	},

	ready ()
	{
		this.load();
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

			onFileChange (file)
			{
				this.load();
				return true;
			},

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
				this.method 	= state.method;
			}

	}

});

export default new Loader();