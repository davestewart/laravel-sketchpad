import _            from 'underscore'
import Vue 		from 'vue';
import store	from './store.js';
import {getArrayChange}	from '../functions/utils';

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

		created ()
		{
			store.$on('load', this.onStoreLoad)
			store.$on('change', this.onStoreChange)
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

				/**
				 * Update the params from a query when the method hasn't changed
				 *
				 * @param params
				 * @returns {State}
				 */
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

				/**
				 * Update the state when a new controller is loaded
				 *
				 * @param {Object}  controller
				 */
				setController (controller)
				{
					// variables
					const oldName = this.method ? this.method.name : null;
					const oldNames = this.controller.methods.map(method => method.name);
					const newNames = controller.methods.map(method => method.name);

					// update
					this.controller = controller;
					if(!this.method)
					{
						return;
					}

					// calculate change
					const change = getArrayChange(newNames, oldNames, oldName);

					// determine action
					let route;
					switch (change.type)
					{
						case 'none':
							this.method = controller.methods[change.index];
							this.$emit('update', this.controller, this.method);
							route = this.makeRoute(this.method);
							break;

						case 'changed': // renamed
							// re-route to method
							this.method = controller.methods[change.index];
							route = this.makeRoute(this.method);
							break;

						case 'modified':
							// re-route to controller
							route = this.makeRoute(this.controller);
							break;

						case 'moved':
							// reload console
							this.method = controller.methods[change.newIndex];
							//route = this.makeRoute(this.method);
							this.$emit('update', this.controller, this.method);
							break;
					}

					// update route
					if (route)
					{
						router.go('/run/' + route);
					}
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
			// handlers

				onStoreLoad (data)
				{
					if (this.controller)
					{
						const controller = store.getControllerByPath(this.controller.path);
						if (controller)
						{
							this.setController(controller)
						}
					}

				},

				onStoreChange (controller)
				{
					if (this.controller && this.controller.path === controller.path)
					{
						this.setController(controller)
					}
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