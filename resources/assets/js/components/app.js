var App = Vue.extend({

	el:function(){ return '#app'; },

	data:function()
	{
		return {
			store		:this.$options.store,
			state		:this.$options.state,
			settings	:this.$options.settings
		};
	},

	ready:function()
	{
		// objects
		this.server		= this.$options.server;

		// reloading
		this.store.$on('load', this.onStoreLoad);

		// links
		//$('body').on('click', 'a[href^="/sketchpad/"]', this.onLinkClick);
		$('body').on('click', 'a', this.onLinkClick);

		// routes
		var url = this.state.baseUrl;
		this.router = new Router();
		this.router.route(url, this.onHome);
		this.router.route(url + '~/*view', this.onView);
		this.router.route(url + '*path', this.onRoute);

		// seems to be a small bug with router on home route, so trigger home then start as normal
		this.onHome();
		this.router.start();

		// ui
		//$('#nav .sticky').sticky({topSpacing:20, bottomSpacing:20});
		//$('#params .sticky').sticky({topSpacing:20});
	},

	methods:
	{

		// ------------------------------------------------------------------------------------------------
		// methods

			run:function(route)
			{
				this.unwatch();
				this.state.setRoute(route);
				this.$nextTick(function()
				{
					this.update();
					if(this.state.method)
					{
						this.watch();
					}
				});
			},

			update:function()
			{
				if(this.$refs.result)
				{
					this.$refs.result.load();
				}
			},

			watch:function()
			{
				this.unwatch = this.$watch('state.method.params', this.onParamsChange, {deep:true});
			},

			unwatch:function()
			{
				// will be populate by $watch
			},


		// ------------------------------------------------------------------------------------------------
		// handlers

			onLinkClick:function(event)
			{
				// variables
				var meta 	= event.ctrlKey || event.metaKey;
				var route	= this.state.getLink(event.target.href);
				var $target	= $(event.target);
				var path	= $target.data('path');
				var href	= $target.attr('href');

				// skip # links
				if(href && href.indexOf('#') == 0)
				{
					return;
				}

				// controller
				if(path && meta)
				{
					event.preventDefault();
					this.server.loadController(path);
				}

				// method
				if(route)
				{
					event.preventDefault();
					meta
						? this.server.open(route)
						: this.router.navigate(route);
				}
			},

			onRoute:function(route)
			{
				this.run(this.state.baseUrl + route);
			},

			onParamsChange:function()
			{
				this.router.navigate(this.state.route, false, true);
			},

			onHome:function()
			{
				this.onView('welcome');
			},

			onView:function(type)
			{
				document.title 	= 'Sketchpad - ' + type;
				this.state.reset();
				this.server.load(':page/' + type, function(html)
				{
					$('#content').html(html);
				});
			},

			onStoreLoad:function(event)
			{
				if(this.state.controller && this.state.controller.path == event.path)
				{
					var cIndex 	= event.index;
					var mIndex	= this.state.method ? this.state.controller.methods.indexOf(this.state.method) : -1;
					if(cIndex > -1)
					{
						this.unwatch();
						this.state.controller = this.store.controllers[cIndex]
						if(mIndex > -1)
						{
							this.state.method = this.state.controller.methods[mIndex];
						}
						this.watch();
					}

					// reload
					this.update();
				}
			}
	}

});
