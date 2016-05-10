var vm =
{

	el:'#app',

	data:function()
	{

		var data = JSON.parse($('#data').text());
		data.controller = null;
		data.method = null;
		data._route = '';
		data.modal = {};
		return data;
	},

	ready:function()
	{
		// data
		this.$refs.navigation.controllers = this.controllers;

		// history
		this.history = new UserHistory(this);

		// front page
		if(this.history.isHome())
		{
			$('#welcome').appendTo('#output').show();
		}
		
		// ui
		$('#nav .sticky').sticky({topSpacing:20});
		//$('#params .sticky').sticky({topSpacing:20});

		// links
		$('body').on('click', 'a', this.onLinkClick);
	},

	computed:
	{
		route:
		{
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

	events:
	{
		onNavClick:function(route)
		{
			this.history.pushState(route);
			this.setRoute(route);
		}

	},

	methods:
	{


		// ------------------------------------------------------------------------------------------------
		// route

			setRoute:function(route)
			{
				// properties
				this.$data._route 	= route;
				var controller 		= this.getController(route);
				var method 			= this.getMethod(route);

				// controller
				this.controller = this.$refs.navigation.controller = controller;
				
				// take action
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
		// pages

			onLinkClick:function(event)
			{
				// variables
				var url		= event.target.href;
				var matches = url.match(/\/:(\w+)/);
				if(matches)
				{
					event.preventDefault();
					this.$refs.modal.load(url);
				}
			}

	}

};
