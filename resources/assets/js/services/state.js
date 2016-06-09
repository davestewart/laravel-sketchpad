;window.State = Vue.extend(
{

	// ------------------------------------------------------------------------------------------------
	// properties

		data:function()
		{
			return {
				baseUrl		:$('meta[name="route"]').attr('content'),
				store		:this.$options.store,
				controller	:null,
				method		:null
			};
		},

		computed:
		{
			route:function()
			{
				return this.makeRoute(this.method, this.controller);
			}
		},

		props:['store'],


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
				setRoute:function(route)
				{
					// data
					var data 				= this.parseRoute(route);

					// state
					this.controller 		= data.controller;
					this.method 			= data.method;
					if(data.method && data.params)
					{
						data.params.forEach(function (value, index)
						{
							var param = data.method.params[index];
							if (param)
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
				reset:function()
				{
					this.controller = null;
					this.method 	= null;
				},

				getLink:function(url)
				{
					url = String(url).replace(location.origin, '');
					return url.indexOf(this.baseUrl) === 0
						? url.replace(/\/*$/, '/')
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
				parseRoute:function(route)
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
						params = route.replace(method.route, '').match(/[^\/]+/g);
					}

					// return
					return {controller:controller, method:method, params:params};
				},

				makeRoute:function(method, controller)
				{
					return method
						? method.route + (method.params.length ? method.params.map(function (p) { return p.value; }).join('/') + '/' : '')
						: controller
							? controller.route
							: '';
				},

				getRoute:function(route)
				{

				}
		}


});