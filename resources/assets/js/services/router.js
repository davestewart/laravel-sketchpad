window.Router = Vue.extend({

	data:function()
	{
		return {
			controllers	:this.$options.controllers,
			state		:this.$options.state
		}
	},

	methods:
	{

		go:function(route)
		{
			// data
			route = route instanceof Route
				? route
				: this.parseRoute(route);

			// assign
			if(route.controller)
			{
				this.state.setRoute(route, true);
			}
		},

		/**
		 * Gets a Route instance from a route string
		 *
		 * @param 	{string}	[route]
		 * @returns {Route}
		 */
		parseRoute:function(route)
		{
			// parameters
			route = route || location.href;
			route = route.replace(location.origin, '');

			// variables
			var controller, method, params;

			// assignments
			controller = this.controllers.filter(function(c) { return route.indexOf(c.route) == 0; }).shift();
			if(controller)
			{
				method = controller.methods.filter(function(m) { return route.indexOf(m.route) == 0; }).shift();
			}
			if(method)
			{
				params = route.replace(method.route, '').match(/[^\/]+/g);
			}

			// return
			return new Route(route, controller, method, params);
		},

		dispatch:function(type, data)
		{
			this.$dispatch('update', {type:type, data:data});
		}

	}

});

function Route(route, controller, method, params)
{
	this.route 		= route || '';
	this.controller	= controller;
	this.method 	= method;
	this.params 	= params;
}

Route.prototype =
{
	route		:null,
	controller	:null,
	method		:null,
	params		:null
};