import Vue 		from 'vue';
import store	from './store.js';

/**
 * Stores the current controller > method > params
 */
var State = Vue.extend({

	// ------------------------------------------------------------------------------------------------
	// properties

		data ()
		{
			return {
				controller	:null,
				method		:null
			};
		},

		computed:
		{
			route ()
			{
				return this.makeRoute(this.method || this.controller);
			}
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
				 * @param {Object}  params      A POJO of name:value properties
				 */
				setRoute (route, params)
				{
					// don't update if route is the same
					if(this.method && route === this.method.route)
					{
						this.setParams(params);
						return false;
					}

					// variables
					let {controller, method} = this.parseRoute(route);

					// if we have a method, update its parameters
					if(method && params)
					{
						this.setParams(params, method)
					}

					// if no method, fall back to index
					if(controller && ! method)
					{
						method      = controller.methods.find(function(m){ return m.name == 'index'; });
					}

					// page title
					document.title 	= 'Sketchpad - ' + route.replace(/\/$/, '').replace(/\//g, ' ▸ ');

					// state
					this.controller = controller;
					this.method 	= method;

					// return
					return true;
				},

				setParams (params)
				{
					if (this.method)
					{
						this.method.params.forEach(param =>
						{
							const name      = param.name;
							const value     = params[name];
							if (typeof value !== 'undefined')
							{
								param.value = value;
							}
						});
					}
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
				 * Parse a route into a controller and method
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
					controller  = store.controllers.filter(function(c) { return route.indexOf(cap(c.route)) === 0; }).shift();
					if(controller)
					{
						method  = controller.methods.filter(function(m) { return route === cap(m.route); }).shift();
					}

					// return
					return {controller, method};
				},

				/**
				 * Make a FQ route from a method or controller
				 *
				 * @param   {Object}    obj
				 * @returns {string}
				 */
				makeRoute (obj)
				{
					if (obj)
					{
						let route = obj.route;
						if(obj.params && obj.params.length)
						{
							return route + '?' + obj.params
									.map( p => p.name + '=' + (p.value || '') )
									.join('&');
						}
						return route;
					}
					return '';
				}
		}

});

function cap (route)
{
	return route.replace(/^\/*|\/*$/g, '/');
}

export default new State;