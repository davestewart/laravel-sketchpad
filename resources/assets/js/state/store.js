import _            from 'underscore';
import Vue 		    from 'vue';
import server	    from '../services/server';
import watcher      from '../services/watcher'

/**
 * The controllers store
 *
 * - loads controller data
 * - stores controller data
 */
const Store = Vue.extend({

	data ()
	{
		const text = $('#data').text();
		return JSON.parse(text);
	},

	created ()
	{
		watcher.addHandler(this.onControllerChange, /Controller\.php$/);
	},

	methods:
	{
		// ------------------------------------------------------------------------------------------------
		// load

			loadAll ()
			{
				server
					.loadController()
					.then(this.onLoadAll)
			},

			load (route)
			{
				server
					.loadController(route)
					.then(this.onLoad)
			},


		// ------------------------------------------------------------------------------------------------
		// handlers

			/**
			 * All controllers load handler
			 *
			 * @param   {Object[]}  data    An array of controller objects
			 */
			onLoadAll (data)
			{
				if (Array.isArray(data))
				{
					this.controllers = data;
					this.$emit('load', data);
				}
				else
				{
					console.warn('controllers data is not an array', data);
				}
			},

			/**
			 * Single controller load handler
			 *
			 * @param   {Object}    data    A single controller object
			 */
			onLoad (data)
			{
				if (data)
				{
					if(data.path)
					{
						// see if the same controller is already loaded
						const controller = this.getControllerByPath(data.path);

						// if so, replace it
						if(controller)
						{
							const index = this.controllers.indexOf(controller);
							this.controllers[index] = data;
							//Vue.set(this.controllers, index, data)
						}

						// if not, add it
						else
						{
							this.controllers.push(controller);
							this.controllers.sort(fnSort);
						}

						// force updates everywhere
						this.controllers.splice();

						// dispatch
						this.$emit('change', data)
					}
				}
			},

			/**
			 * Update controller data when a controller file is changed on disk
			 *
			 * @param   {string}    path    The absolute path to a controller file (possibly loaded)
			 * @param   {string}    type    The file change type; change, add, delete
			 * @returns {boolean}
			 */
			onControllerChange (path, type)
			{
				// console.log('store: controller change')
				if (type === 'add')
				{
					this.loadAll();
					return true;
				}

				const controller = this.getControllerByPath(path);
				if (controller)
				{
					// delete controller
					if (type === 'delete')
					{
						const index = this.controllers.indexOf(controller);
						this.controllers.splice(index, 1);
						this.$emit('delete', controller);
						return true;
					}

					// update controller
					this.load(controller.route);
					return true;
				}

				return false;
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