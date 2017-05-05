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
		watcher.addHandler(this.onControllerChange, file => /Controller\.php$/.test(file));
	},

	methods:
	{
		// ------------------------------------------------------------------------------------------------
		// load

			loadAll ()
			{
				return server
					.loadController()
					.then(this.onLoadAll)
			},

			load (route)
			{
				return server
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
					// merge existing controller parameter values into incoming controller values
					data.forEach(trgController => {
						const srcController = this.getControllerByPath(trgController.path);
						if (srcController)
						{
							updateController(trgController, srcController);
						}
					});

					// update
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
						const trgController = data;
						const srcController = this.getControllerByPath(trgController.path);

						// if so, replace it
						if(srcController)
						{
							const index = this.controllers.indexOf(srcController);
							this.controllers[index] = updateController(trgController, srcController);
						}

						// if not, add it
						else
						{
							this.controllers.push(trgController);
							this.controllers.sort(fnSort);
						}

						// force updates everywhere
						this.controllers.splice();

						// dispatch
						this.$emit('change', trgController)
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

				// add controller
				if (type === 'add')
				{
					this.loadAll()
						.then(() => {
							const controller = this.getControllerByPath(path);
							if (controller)
							{
								this.$emit('add', controller);
							}
						});
					return true;
				}

				// existing controller
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

				// fallback for new controllers, not yet added
				this.onControllerChange(path, 'add')
				return true;
			},


		// ------------------------------------------------------------------------------------------------
		// utilities

			getControllerByPath (path)
			{
				return this.controllers.find( controller => controller.path === path );
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

/**
 * Copies existing controller parameter values to incoming controller parameters
 *
 * @param   {Object}    trgController
 * @param   {Object}    srcController
 * @returns {Object}
 */
function updateController(trgController, srcController)
{
	// cache source methods and params as named hashes
	const srcMethods = srcController.methods.reduce((methods, method) => {
		methods[method.name] = method.params.reduce((params, param) => {
			params[param.name] = param;
			return params;
		}, {});
		return methods;
	}, {});

	// loop over target methods and assign source method param values
	trgController.methods.forEach(trgMethod => {
		const srcParams = srcMethods[trgMethod.name]
		if(srcParams)
		{
			// loop over target params and assign source param values
			trgMethod.params.forEach(trgParam => {
				trgParam.default = trgParam.value
				const srcParam = srcParams[trgParam.name]
				if (srcParam)
				{
					trgParam.value = srcParam.default !== trgParam.default
						? trgParam.value
						: srcParam.value;
				}
			})
		}
	})

	// return
	return trgController;
}
