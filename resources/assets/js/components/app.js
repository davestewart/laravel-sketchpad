var vm =
{

	el:'#app',

	data:function()
	{

		var data = JSON.parse($('#data').text());
		data.controller = null;
		data.method = null;
		data._route = '';
		return data;
	},

	ready:function()
	{
		$('#sticky').sticky({topSpacing:20});
		//$('#params').sticky({topSpacing:20});
	},

	computed:{

		route:{
			get:function ()
			{
				return this.$data._route;
			},
			set:function (route)
			{
				this.setRoute(route);
			}
		}

	},

	methods:
	{

		// ------------------------------------------------------------------------------------------------
		// route

			setRoute:function(route)
			{
				this.$data._route 	= route;
				this.controller 	= this.getController(route);
				var method 			= this.getMethod(route);


				if(method)
				{
					this.$broadcast('loadMethod', method);
					this.method = method;
				}
				else if(this.controller)
				{
					this.$broadcast('loadController', this.controller);
				}
			},

			getController:function (route)
			{
				var arr = this.controllers.filter(function(e)
				{
					return route.indexOf(e.route) == 0;
				});
				return arr ? arr[0] : null;
			},

			getMethod:function (route, controller)
			{
				controller = controller || this.controller || this.getController(route);
				if(controller)
				{
					var arr = controller.methods.filter(function(e)
					{
						return route.indexOf(e.route) == 0;
					});
					return arr ? arr[0] : null;
				}
			},


		// ------------------------------------------------------------------------------------------------
		// handlers

			onControllerClick:function(controller)
			{
				hist.pushState(controller.route);
				this.route = controller.route;
			},

			onMethodClick:function(method, element, $http)
			{
				if(event.metaKey || event.ctrlKey)
				{
					return server.open(event.target.href);
				}
				hist.pushState(method.route);
				this.route = method.route;
			},


		// ------------------------------------------------------------------------------------------------
		// utilities

			getLinkHtml:function(route)
			{
				return route
					.replace('/sketchpad/', '')
					.replace(/\/$/, '')
					.split('/')
					.join(' <span class="divider">&#9656;</span> ');
			},

			isActive:function(route)
			{
				return this.$data._route.indexOf(route) == 0;
			}

	}

};
