import Vue 		from 'vue';
import store	from './store.js';
import {clone}	from '../functions/utils';

/**
 * Stores the current controller > method > params
 */
const State = Vue.extend({

	// ------------------------------------------------------------------------------------------------
	// properties

		data ()
		{
			return {
				controller	: null,
				method		: null
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
				 * @param {Object}  query       A POJO of name:value properties
				 */
				setRoute (route, query)
				{
					// if method is the same, update query only
					if(this.method && route === this.method.route)
					{
						this.setQuery(query);
						return false;
					}

					// otherwise, get new objects
					let {controller, method} = this.parseRoute(route);

					// if no method, fall back to index
					if(!method && controller)
					{
						method = controller.methods.find(function(m){ return m.name == 'index'; });
					}

					// state
					if (controller !== this.controller)
					{
						this.controller = controller;
					}
					this.method = method;

					// if we have a method, update its parameters
					this.setQuery(query);

					// return
					return true;
				},

				setQuery (params)
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
					return this;
				},

				setParams (params)
				{
					if (this.method)
					{
						this.method.params = params;
					}
					return this;
				},

				/**
				 * Reset all values
				 */
				reset ()
				{
					this.controller = null;
					this.method 	= null;
					return this
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