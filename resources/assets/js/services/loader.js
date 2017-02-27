import Vue 		from 'vue';

// objects
import watcher	from './watcher';
import server	from './server';
import state    from '../state/state'

export const LoaderEvent = {
	START    : 'start',
	LOAD     : 'load',
	ERROR    : 'error',
};

export const Loader = Vue.extend({

	data ()
	{
		return {
			loading		:false,
			transition	:false,
			method	    :null
		}
	},

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
		watcher.addHandler(/\.php$/, this.onFileChange);
	},

	methods:
	{
		// ------------------------------------------------------------------------------------------------
		// main methods

			load ()
			{
				if (!state.method)
				{
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
				this.$emit(LoaderEvent.START, this.transition);
				server.run(state.method, this.onLoad, this.onFail, this.onComplete);
			},


		// ------------------------------------------------------------------------------------------------
		// events

			onFileChange (file, type)
			{
				this.load();
				return true;
			},

			onParamsChange (method)
			{
				//console.log('PARAM!', method);
				//router.replace('/run/' + state.makeRoute(state.method));
			},

			onLoad (data, status, xhr)
			{
				const type = xhr.getResponseHeader('Content-Type');
				if (state.method)
				{
					state.method.error = 0;
				}
				this.$emit(LoaderEvent.LOAD, data, type);
			},

			onFail (xhr, status, message)
			{
				if (state.method)
				{
					state.method.error = 1;
				}
				var data	= xhr.responseText;
				var type	= xhr.getResponseHeader('Content-Type');
				this.$emit(LoaderEvent.ERROR, data, type);
			},

			onComplete ()
			{
				this.method 	= state.method;
			}

	}

});

export default new Loader();