import Vue 		    from 'vue';
import server	    from '../services/server/server';
import state        from '../state/state'
import livereload   from '../services/livereload'

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
		const text = $('#data').text();
		const data = JSON.parse(text);
		data.controller = data.controllers[0];
		data.method = data.controller.methods[0];
		return data;
	},

	created ()
	{
		livereload(this)
	},

	methods:
	{

		// ------------------------------------------------------------------------------------------------
		// loading

			reloadAll ()
			{
				server
					.load('api/load')
					.then(this.onLoadAll)
			},

			/**
			 * Delegated livereload function
			 *
			 * @param   {string}    path    A rootrelative path; i.e. sketchpad/controllers/ExampleController.php
			 * @returns {boolean}           A
			 */
			reload (path)
			{
				// intercept controller updates
				if (/Controller\.php$/.test(path))
				{
					const controller = this.getControllerByPath(path);
					if(controller)
					{
						// console.log('controller changed:', event);
						server.loadController(controller.route, this.onLoad);
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

		// ------------------------------------------------------------------------------------------------
		// loading

			setControllers (data)
			{
				this.controllers = data;
				const controller = this.getControllerByPath(state.controller.path);
				if (controller)
				{
					this.updateState(controller)
				}
			},

			replaceController (old, controller)
			{
				const index = this.controllers.indexOf(old);
				this.controllers[index] = controller;
				if (state.controller.path === controller.path)
				{
					this.updateState(controller)
				}
			},

			addController (controller)
			{
				this.controllers.push(controller);
				this.controllers.sort(fnSort);
			},

			updateState (controller)
			{
				// index of existing method on existing controller
				const index = state.controller.methods.indexOf(state.method);

				// replace controller and method
				state.controller = controller;
				state.method = controller.methods[index];
			},


		// ------------------------------------------------------------------------------------------------
		// handlers

			/**
			 * Update controller data when a controller is changed, requested, and data reloaded
			 *
			 * @param data
			 */
			onLoad (data)
			{
				// console.log('onLoad called', data)
				if(data && data.path)
				{
					const controller = this.getControllerByPath(data.path);
					controller
						? this.replaceController(controller, data)
						: this.addController(data)
				}
			},

			onLoadAll (data)
			{
				this.setControllers(data);
			},


		// ------------------------------------------------------------------------------------------------
		// utilities

			getControllerByPath (path)
			{
				return this.controllers.find( c => c.path === path );
			}

	}

});

export default new Store();

function fnSort (a, b)
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
}