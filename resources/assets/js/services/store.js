import Vue 		from 'vue';
import server	from './server.js';

var Store = Vue.extend({

	data:function()
	{
		// object with single controllers property
		return JSON.parse($('#data').text());
	},

	created:function()
	{
		if(window.LiveReload)
		{
			// server
			this.server = server;

			// proxies
			var reload 	= LiveReload.reloader.reload;
			var self	= this;

			// monkeypatch livereloader
			LiveReload.reloader.reload = function(path, options)
			{
				if(self.reload(path))
				{
					return;
				}
				return reload.call(this, path, options);
			};
		}
	},

	methods:
	{

		// ------------------------------------------------------------------------------------------------
		// loading

			/**
			 * Delegated livereload function
			 *
			 * @param path
			 * @returns {boolean}
			 */
			reload:function(path)
			{
				// intercept controller updates
				if (/Controller\.php$/.test(path))
				{
					var controller = this.getControllerByPath(path);
					if(controller)
					{
						this.server.loadController(path, this.onLoad);
					}
					return true;
				}

				// php file
				if(/\.php$/.test(path))
				{
					this.dispatch('file');
					return true;
				}

				// let LiveReload handle the load
				return false;
			},

			/**
			 * Update controller data when a controller is changed, requested, and data reloaded
			 *
			 * @param data
			 */
			onLoad:function(data)
			{
				if(data && data.path)
				{
					// check for existing controller
					var controller = this.getControllerByPath(data.path);
					var index;

					// insert if the controller exists
					if(controller)
					{
						// update store
						index = this.controllers.indexOf(controller);
						this.controllers.$set(index, data);
					}

					// append and sort if not
					else
					{
						this.controllers.push(data);
						this.controllers.sort(function(a, b)
						{
							if(a.path < b.path)
							{
								return -1;
							}
							if(a.path > b.path)
							{
								return 1;
							}
							return 0;
						});
					}

					// dispatch
					this.dispatch('controller', data.path, index);
				}
			},


		// ------------------------------------------------------------------------------------------------
		// utilities

			getControllerByPath:function(path)
			{
				return this.controllers.filter(function(c){ return c.path == path; }).shift();
			},

			dispatch:function(type, path, index)
			{
				this.$dispatch('load', {type:type, path:path, index:index});
			}

	}

});

export default new Store();