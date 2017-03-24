import Vue 		    from 'vue';
import server	    from '../services/server';
import state        from '../state/state'
import watcher      from '../services/watcher'

/**
 * The controllers store
 *
 * - loads controller data
 * - stores controller data
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
		watcher.addHandler(/Controller\.php$/, this.onControllerChange);
	},

	methods:
	{
		// ------------------------------------------------------------------------------------------------
		// load

			loadAll ()
			{
				server
					.load('api/load')
					.then(this.onLoadAll)
			},

			load (route)
			{
				server
					.load('api/load/' + route)
					.then(this.onLoad)
			},


		// ------------------------------------------------------------------------------------------------
		// handlers

			/**
			 * Update controller data when a controller is changed, requested, and data reloaded
			 *
			 * @param   {Object[]}  data    An array of controller data
			 */
			onLoadAll (data)
			{
				this.setControllers(data);
			},

			/**
			 * Handles a watcher file change
			 *
			 * @param   {string}    path   The absolute path to a controller file (possibly loaded)
			 * @returns {boolean}
			 */
			onControllerChange (path, type)
			{
				const controller = this.getControllerByPath(path);
				if(controller)
				{
					this.load(controller.route);
					return true;
				}
				else if (type === 'add' || type === 'delete')
				{
					this.loadAll();
					return true;
				}
				return false;
			},

			/**
			 * Single controller load handler
			 *
			 * @param   {Object}    data    Controller data
			 */
			onLoad (data)
			{
				if(data)
				{
					if(data.path)
					{
						this.setController(data)
					}
					if (data.error)
					{
						console.log(data.error);
						this.loadAll();
					}
				}
			},


		// ------------------------------------------------------------------------------------------------
		// update

			/**
			 * Update all controllers
			 *
			 * @param   {Object[]}  data        An array of controllers
			 */
			setControllers (data)
			{
				this.controllers = data;
				if (state.controller)
				{
					const controller = this.getControllerByPath(state.controller.path);
					if (controller)
					{
						this.updateState(controller)
					}
				}
			},

			setController (data)
			{
				// see if the same controller is already loaded
				const controller = this.getControllerByPath(data.path);

				// if so, replace it
				if(controller)
				{
					const index = this.controllers.indexOf(controller);
					this.controllers[index] = data;
					if (state.controller && state.controller.path === data.path)
					{
						this.updateState(data);
					}
				}

				// if not, add it
				else
				{
					this.controllers.push(controller);
					this.controllers.sort(fnSort);
				}
			},

			/**
			 * Update the state object after controller update
			 *
			 * @param {Object}  controller
			 */
			updateState (controller)
			{
				// index of existing method on existing controller
				const index = state.controller.methods.indexOf(state.method);

				// replace controller and method
				state.controller = controller;
				if (controller.methods)
				{
					state.method = controller.methods[index];
				}
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
	return  a.path < b.path
		? -1
		: (a.path > b.path
			? 1
			: 0);
}