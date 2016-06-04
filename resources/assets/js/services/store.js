;window.Store = Vue.extend({

	data:function()
	{
		// object with single controllers property
		var data 	= JSON.parse($('#data').text());

		// state object
		data.state 	= this.$options.state || state;

		// return
		return data;
	},

	created:function()
	{
		if(LiveReload)
		{
			// server
			this.server = this.$options.server || server;

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
						this.server.load(':load/' + path, this.onLoad);
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
			 * Called when AJAX response with new controller data comes back from server
			 *
			 * @param data
			 */
			onLoad:function(data)
			{
				if(data && data.path)
				{
					// check for existing controller
					var controller = this.getControllerByPath(data.path);

					// insert if the controller exists
					if(controller)
					{
						// update store
						var index = this.controllers.indexOf(controller);
						this.controllers.$set(index, data);

						// update state if current controller was reloaded
						if(this.state.controller == controller)
						{
							var methodIndex = this.state.controller.methods.indexOf(this.state.method);
							this.state.controller = data;
							if(methodIndex > -1)
							{
								this.state.method = this.state.controller.methods[methodIndex];
							}
						}
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
					this.dispatch('controller', data.path);
				}
			},


		// ------------------------------------------------------------------------------------------------
		// utilities

			getControllerByPath:function(path)
			{
				return this.controllers.filter(function(c){ return c.path == path; }).shift();
			},

			dispatch:function(type, path)
			{
				this.$dispatch('load', {type:type, path:path});
			}

	}

});

