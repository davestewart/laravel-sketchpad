import Vue 		from 'vue';
import server	from '../services/server/server';
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
		return data ? JSON.parse(data) : {};
	},

	created ()
	{
		const self = this;

		if(window.LiveReload)
		{
			// server
			this.server = server;

			// proxies
			const reload 	= LiveReload.reloader.reload;

			// monkeypatch livereloader
			LiveReload.reloader.reload = function(file, options)
			{
				if(self.reload(file))
				{
					return;
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
						this.server.loadController(path, this.onLoad);
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
				if(data && data.path)
				{
					// check for existing controller
					const controller = this.getControllerByPath(data.path);
					let index;

					// insert if the controller exists
					if(controller)
					{
						// debug
						console.log('Updating controller: ' + data.name);

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
				return this.controllers.filter( c => c.path == path ).shift();
			},

			emit (type, path, index)
			{
				this.$emit('load', {type:type, path:path, index:index});
			}

	}

});

export default new Store();