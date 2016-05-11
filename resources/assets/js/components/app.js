var vm =
{

	el:'#app',

	data:function()
	{
		// controllers
		var data = JSON.parse($('#data').text());

		// props
		data._route = '';
		data.controller = null;
		data.method = null;
		data.modal = {};
		data.options =
		{
			useLabels:true
		};

		// return
		return data;
	},

	ready:function()
	{
		// objects
		this.server		= new Server();
		this.history 	= new UserHistory(this);

		// front page
		if(this.history.isHome())
		{
			$('#welcome').appendTo('#output').show();
		}

		// ui
		//$('#nav .sticky').sticky({topSpacing:20, bottomSpacing:20});
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
				// params
				route 				= route.replace(/\/*$/, '/');

				// properties
				this.$data._route 	= route;
				this.controller 	= this.getController(route);
				var method 			= this.getMethod(route);

				// take action
				if(method)
				{
					this.method = method;
					this.$broadcast('loadMethod', method);
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
		// dom event handlers

			onLinkClick:function(event)
			{
				// variables
				var url		= event.target.href;
				var matches = url.match(/\/:(\w+)\/(\w+)/);
				if(matches)
				{
					event.preventDefault();
					this.$refs.modal.load(url);
				}
			},

			onControllerReload:function(data)
			{
				if(data)
				{
					var filtered = this.controllers.filter(function(c){ return c.path.toLowerCase() == data.path.toLowerCase(); });
					if(filtered.length)
					{
						var found = filtered[0];
						var index = this.controllers.indexOf(found);
						this.controllers.$set(index, data);
					}
					else
					{
						this.controllers.push(data);
						this.controllers.sort(function(a, b){
							if(a.path < b.path)
							{
								return -1;
							}
							if(a.path > b.path)
							{
								return 1;
							}
							return 0;
						});
					}
					this.setRoute(this.route);
				}
			},


		// ------------------------------------------------------------------------------------------------
		// other

			reloadController:function(file)
			{
				this.server.load(':load/' + file, this.onControllerReload);
			}

	}

};
