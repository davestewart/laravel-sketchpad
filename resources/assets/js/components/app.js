var App = Vue.extend({

	el:function(){ return '#app'; },

	data:function()
	{
		return {
			store		:this.$options.store,
			state		:this.$options.state,
			options		:
			{
				useLabels:true
			}
		};
	},

	ready:function()
	{
		// objects
		this.server		= this.$options.server;
		this.router 	= new Router({controllers:this.store.controllers, state:this.state});

		// history
		window.onpopstate = this.onPopState;

		// start
		this.state.setRoute(this.router.parseRoute());

		// front page
		if(this.state.route == '')
		{
			$('#welcome').appendTo('#output').show();
		}

		// ui
		//$('#nav .sticky').sticky({topSpacing:20, bottomSpacing:20});
		//$('#params .sticky').sticky({topSpacing:20});

		// links
		$('body').on('click', 'a', this.onLinkClick);
	},

	methods:
	{

		// ------------------------------------------------------------------------------------------------
		// handlers

			onPopState:function(event)
			{
				if(event.state)
				{
					this.state.setRoute(event.state);
				}
			},

			onLinkClick:function(event)
			{
				// variables
				var url		= event.target.href;
				var route 	= this.router.parseRoute(url);

				// controller
				if(route.controller)
				{
					event.preventDefault();
					route.method && (event.metaKey || event.ctrlKey)
						? this.server.open(url)
						: this.router.go(route);
				}

				// modal
				else if(url.match(/\/:(\w+)\/(\w+)/))
				{
					event.preventDefault();
					this.$refs.modal.load(url);
				}

			}

	}

});
