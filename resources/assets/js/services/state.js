import Vue 		from 'vue';
import store	from './store.js';

var State = Vue.extend({

	// ------------------------------------------------------------------------------------------------
	// properties

		el: () => document.createElement('div'),

		data ()
		{
			return {
				baseUrl		:$('meta[name="route"]').attr('content'),
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
			//console.log(this);
		},


	// ------------------------------------------------------------------------------------------------
	// methods

		methods:
		{

			// ------------------------------------------------------------------------------------------------
			// public methods

				/**
				 * Set values from route string
				 *
				 * @param route
				 */
				setRoute (route)
				{
					// data
					var data 				= this.parseRoute(route);

					// state
					this.controller 		= data.controller;
					this.method 			= data.method;
					if(data.method && data.params)
					{
						data.method.params.forEach(function (param, index)
						{
							var name    = param.name;
							var value   = data.params[name];
							if (typeof value !== 'undefined')
							{
								param.value = value;
							}
						});
					}

					// index fallback
					if(this.controller && ! this.method)
					{
						var methods = this.controller.methods.filter(function(m){ return m.name == 'index'; });
						if(methods.length)
						{
							this.method = methods.shift();
						}
					}

					// page
					var title		= 'Sketchpad - ' + this.route.replace(this.baseUrl, '');
					document.title 	= title.replace(/\/$/, '').replace(/\//g, ' ▸ ');
				},

				/**
				 * Rest all values
				 */
				reset ()
				{
					this.controller = null;
					this.method 	= null;
				},

				getLink (url)
				{
					url = String(url).replace(location.origin, '');
					return url.indexOf(this.baseUrl) === 0
						? url
						: null;
				},


			// ------------------------------------------------------------------------------------------------
			// private methods

				/**
				 * Gets a Route instance from a route string
				 *
				 * @param 	{string}	[route]
				 * @returns {object}
				 */
				parseRoute (route)
				{
					// parameters
					route = this.getLink(route || location.href);

					// variables
					var controller, method, params;

					// assignments
					controller = this.store.controllers.filter(function(c) { return route.indexOf(c.route) == 0; }).shift();
					if(controller)
					{
						method = controller.methods.filter(function(m) { return route.indexOf(m.route) == 0; }).shift();
					}
					if(method)
					{
						params = location.search.substr(1).split('&')
							.reduce( function (params, p) {
								let [name, value] = p.split('=');
								params[name] = decodeURI(value);
								return params;
							}, {});
					}

					// return
					return {controller, method, params};
				},

				makeRoute (method, controller)
				{
					if(method)
					{
						var route = method.route;
						if(method.params.length)
						{
							var params = method.params.map( p => p.name + '=' + (p.value || '') );
							route += '?' + params.join('&');
						}
						return route;
					}
					return controller
						? controller.route
						: '';
				},

				getRoute (route)
				{

				},

				parseQuery ()
				{

				}
		}

});

export default new State;