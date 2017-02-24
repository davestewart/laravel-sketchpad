import Vue 		from 'vue';
import server	from '../services/server/server';
import state    from '../state/state'

/**
 * The controllers store
 *
 * - loads controller data
 * - stores controller data
 * - hooks into live reloading
 */
var Store = Vue.extend({

	data ()
	{
		// object with single controllers property
		var data = $('#data').text();
		data = JSON.parse(data)
		//console.log(data)
		return data ? data : {};
	},

	created ()
	{
		const self = this;
		this.server = server;

		if(window.LiveReload)
		{
			// proxies
			const reload = LiveReload.reloader.reload;

			// monkeypatch livereloader
			LiveReload.reloader.reload = function(file, options)
			{
				if(self.reload(file))
				{
					return true;
				}
				return reload.call(this, file, options);
			};
		}

		if(window.___browserSync___)
		{
			___browserSync___.socket.on('sketchpad.udpate', function(event) {
				console.log(arguments);
				// TODO check this is the proper call
				self.reload(event.file)
			})
		}
	},

	methods:
	{

		// ------------------------------------------------------------------------------------------------
		// loading

			reloadAll ()
			{
				this.server
					.load('api/load')
					.then(this.setControllers)
			},

			setControllers (data)
			{
				this.controllers = data
			},

			/**
			 * Delegated livereload function
			 *
			 * @param path
			 * @returns {boolean}
			 */
			reload (path)
			{
				// intercept controller updates
				if (/Controller\.php$/.test(path))
				{
					const controller = this.getControllerByPath(path);
					if(controller)
					{
						console.log('controller changed:', event)
						this.server.loadController(controller.route, this.onLoad);
					}
					return true;
				}

				// php file
				if(/\.php$/.test(path))
				{
					this.emit('file');
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
			onLoad (data)
			{
				console.log('onLoad called', data)
				if(data && data.path)
				{
					// check for existing controller
					const controller = this.getControllerByPath(data.path);
					let index;

					// insert if the controller exists
					if(controller)
					{
						// index
						index = this.controllers.indexOf(controller);
						console.log('Updating controller: ', index);

						// update store

						console.log('Updating controller: ', controller.path);
						console.log('Old method: ', controller.methods[0].label);
						console.log('New method: ', data.methods[0].label);
						this.controllers[index] = data;

						console.log('Controller updated: ' + this.controllers[index].methods[0].label);
						//Vue.set(app.store.controllers, index, data);

						// update state
						if (state.controller.path === data.path) {
							var mIndex = state.controller.methods.indexOf(state.method);
							state.controller = data;
							state.method = data.methods[mIndex];
						}

					}

					// append and sort if not
					else
					{
						this.controllers.push(data);
						this.controllers.sort(function(a, b)
						{
							if (a.path < b.path)
							{
								return -1;
							}
							if (a.path > b.path)
							{
								return 1;
							}
							return 0;
						});
					}

					// emit
					this.emit('controller', data.path, index);
				}
			},


		// ------------------------------------------------------------------------------------------------
		// utilities

			getControllerByPath (path)
			{
				return this.controllers.find( c => c.path === path );
			},

			emit (type, path, index)
			{
				this.$emit('load', {type:type, path:path, index:index});
			}

	}

});

export default new Store();