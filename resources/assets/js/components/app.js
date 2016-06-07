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
		$('body').on('click', 'a[href^="/sketchpad/"]', this.onLinkClick);

		// routes
		this.router = new Router();
		this.router.route('/sketchpad/', this.onHome);
		this.router.route('/sketchpad/~/:view', this.onView);
		this.router.route('/sketchpad/*path', this.onRoute);
		//this.router.start();

		// page load
		this.run(location.pathname);

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
				this.$refs.result.method = this.state.method;
				this.update(true);
				if(this.state.method)
				{
					this.watch();
				}
			},

			update:function(run)
			{
				this.state.method
					? this.$refs.result.load(run)
					: this.$refs.result.clear();
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
				event.preventDefault();
				var href = $(event.target).attr('href');
				this.router.navigate(href);
			},

			onRoute:function(route)
			{
				this.run(this.state.baseUrl + route);
			},

			onParamsChange:function()
			{
				var route = this.state.route;
				this.router.navigate(route, false, true);
				this.update();
			},

			onHome:function()
			{
				// need to do this with reactive properties
				$('#welcome').appendTo('#output').show();
				this.state.reset();
			},

			onView:function(params)
			{
				console.log('view:', params);
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
