import Vue 		from 'vue';
import store	from './store.js';

/**
 * Stores the current controller > method > params
 */
var State = Vue.extend({

	// ------------------------------------------------------------------------------------------------
	// properties

		el: () => document.createElement('div'),

		data ()
		{
			return {
				store		:store,
				controller	:null,
				method		:null
			};
		},

		computed:
		{
			route ()
			{
				return this.makeRoute(this.method, this.controller);
			}
		},

		//props:['store'],

		created ()
		{
			console.log(this);
		},


	// ------------------------------------------------------------------------------------------------
	// methods

		methods:
		{

			// ------------------------------------------------------------------------------------------------
			// public methods

				/**
				 * Update current values from route string and params object
				 *
				 * @param {string}  route       A route string that matches a controller route (so, not including /sketchpad/run/ prefix)
				 * @param {Object}  params      A POJO containing name:value pairs
				 */
				setRoute (route, params)
				{
					// variables
					let {controller, method} = this.parseRoute(route);

					// state
					this.controller 		= controller;
					this.method 			= method;

					// update parameters
					if(method && params)
					{
						method.params.forEach(function (param, index)
						{
							const name      = param.name;
							const value     = params[name];
							if (typeof value !== 'undefined')
							{
								param.value = value;
							}
						});
					}

					// index fallback
					if(controller && ! this.method)
					{
						const methods = controller.methods.filter(function(m){ return m.name == 'index'; });
						if(methods.length)
						{
							this.method = methods.shift();
						}
					}

					// page
					document.title 	= 'Sketchpad - ' + route.replace(/\/$/, '').replace(/\//g, ' ▸ ');
				},

				/**
				 * Rest all values
				 */
				reset ()
				{
					this.controller = null;
					this.method 	= null;
				},


			// ------------------------------------------------------------------------------------------------
			// private methods

				/**
				 * Gets a Route instance from a route string
				 *
				 * @param 	{string}	route
				 * @returns {object}
				 */
				parseRoute (route)
				{
					// variables
					let controller, method;

					// assignments
					route       = cap(route);
					controller  = this.store.controllers.filter(function(c) { return route.indexOf(cap(c.route)) === 0; }).shift();
					if(controller)
					{
						method  = controller.methods.filter(function(m) { return route === cap(m.route); }).shift();
					}

					// return
					return {controller, method};
				},

				/**
				 * Make a route string from a controller and method
				 *
				 * @param   {Object}    method
				 * @param   {Object}    controller
				 * @returns {string}
				 */
				makeRoute (method, controller)
				{
					if(method)
					{
						let route = method.route;
						if(method.params.length)
						{
							let params = method.params.map( p => p.name + '=' + (p.value || '') );
							route += '?' + params.join('&');
						}
						return route;
					}
					return controller
						? controller.route
						: '';
				}
		}

});

function cap (route)
{
	return route.replace(/^\/*|\/*$/g, '/');
}

export default new State;